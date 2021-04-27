<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Blog;
use App\Website;
use App\BlogIndustry;
use App\User;
use App\InnerBlog;
use App\AdminHistory;
use App\AngelInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Helpers\AngelInvoiceHelper;
use App\Http\Helpers\WebsiteHelper;
class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Clients') )
            return redirect('/webadmin');

        $query = Client::where('archived', 0)
            ->with('clientLead')
            ->with('projectManager');

        $this->applyFilters($query);
        
        $clients = $query->get();

        $archivedClients = Client::where('archived', 1)->get();

        $apiClients = AngelInvoiceHelper::getClients(true);

        $totalBalance = 0;
        foreach( $clients as $client ){
            if( !empty($client->api_id) && isset($apiClients[$client->api_id]) )
                $totalBalance += floatval($apiClients[$client->api_id]['balance']);
        }

        return view('manage-client.client-list', [
            'currentSection'        => 'client-list',
            'clients'               => $clients,
            'archivedClients'       => $archivedClients,
            'apiClients'            => $apiClients,
            'totalBalance'          => $totalBalance
        ]);
    }

    /**
     * Client History
     */
    public function clientHistory(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Clients') )
            return redirect('/webadmin');

        $client = Client::find($request->get('clientId'));

        $data = [
            'currentSection'    => 'client-list'
        ];

        if( is_null($client) ) {
            abort(404);
        }

        $websiteIds = array_column($client->websites()->get()->toArray(), 'id');

        $pendingBlogs = Blog::whereIn('website_id', $websiteIds)
                            ->where('marked', 0)
                            ->where('name', '!=', 'N/A')->orderByDesc('desired_date')->get();
        $completedBlogs = Blog::whereIn('website_id', $websiteIds)
                            ->where('marked', 1)
                            ->where('name', '!=', 'N/A')->orderByDesc('desired_date')->get();

        $pendingJobs    = InnerBlog::whereIn('website_id', $websiteIds)
                                    ->where('marked', 0)->get();
        $completedJobs  = InnerBlog::whereIn('website_id', $websiteIds)
                                    ->where('marked', 1)->get();

        if( $client )
        {
            $data['client']         = $client;
        }

        $data['blogIndustries'] = BlogIndustry::orderBy('name')->get();
        $data['admins']         = User::where('type', User::USER_TYPE_EMPLOYEE)
            ->orderBy('name')->get();

        $data['pendingBlogs']   = $pendingBlogs;
        $data['completedBlogs'] = $completedBlogs;
        $data['pendingJobs']    = $pendingJobs;
        $data['completedJobs']  = $completedJobs;

        $data['allWebsiteTypes']        = WebsiteHelper::getAllWebsiteTypes();
        $data['allAffiliateTypes']      = WebsiteHelper::getAllWebsiteAffiliates();
        $data['allDNSTypes']            = WebsiteHelper::getAllWebsiteDNS();
        $data['allPaymentGateways']     = WebsiteHelper::getAllPaymentGateways();
        $data['allEmailTypes']          = WebsiteHelper::getAllEmailTypes();
        $data['allSitemapTypes']        = WebsiteHelper::getAllSitemapTypes();
        $data['allLeftReviewTypes']     = WebsiteHelper::getAllLeftReviewTypes();
        $data['allPortfolioTypes']      = WebsiteHelper::getOnPortfolioTypes();
        $data['allYextTypes']           = WebsiteHelper::getYextTypes();

        $blogIndustries = BlogIndustry::orderBy('name')->get();
        $prettyBlogIndustries = [];
        foreach( $blogIndustries as $industry ){
            $prettyBlogIndustries[] = [
                'value' => $industry->id,
                'text'  => $industry->name
            ];
        }
        $data['allIndustries']            = $prettyBlogIndustries;

        return view("manage-client.client-history", $data);
    }

    /**
     * Add Blog Client
     */
    public function addClient(Request $request)
    {
        if( $request->isMethod('get') ) {

            return view('manage-client.add-client', [
                'currentSection'    => 'client-list',
            ]);
        }
        else if( $request->isMethod('post') ) {
            $data = $request->all();
            $client = new Client($data);
            $client->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'add client',
                'message'   => 'Add client : ' . $client->name,
                'ref'       => $client->id
            ]);

            Session::flash('message', 'Client is added successfully! Now you can add websites');
            Session::flash('alert-class', 'alert-success');

            return redirect('/client-history?clientId=' . $client->id);
        }
    }

    /**
     * Edit Blog Client
     */
    public function editClient(Request $request, $clientId)
    {
        $client = Client::find($clientId);
        if( !$client )
            abort(404);

        if( $request->isMethod('post') ) {
            $client->name       = $request->name;
            $client->notes      = $request->notes;
            $client->contacts   = $request->contacts;
            $client->client_lead = $request->client_lead;
            $client->project_manager = $request->project_manager;
            $client->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'edit client',
                'message'   => 'Edit client : ' . $client->name,
                'ref'       => $client->id
            ]);

            Session::flash('message', 'Client updated successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect("client-history?clientId=" . $client->id);
        }
    }

    /**
     * Delete Client
     */
    public function deleteClient(Request $request, $clientId)
    {
        $client = Client::find($clientId);

        if( !$client )
            abort(404);

        if( $request->isMethod('get') ) {
            return view('manage-client.delete-client', [
                'currentSection'    => 'client-list',
                'client' => $client
            ]);
        }
        else {

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'delete client',
                'message'   => 'Delete client : ' . $client->name,
                'ref'       => $client->id
            ]);

            $client->delete();


            Session::flash('message', 'Client is removed successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/blog-dashboard');
        }
    }

    /**
     * Archive Client
     */
    public function archiveClient(Request $request, $clientId)
    {
        $client = Client::find($clientId);

        if( !$client )
            abort(404);

        if( $request->isMethod('get') ) {
            return view('manage-client.archive-client', [
                'currentSection'    => 'client-list',
                'client' => $client
            ]);
        }
        else {

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'archive client',
                'message'   => 'Archive client : ' . $client->name,
                'ref'       => $client->id
            ]);

            $client->archived = true;
            $client->archived_at = Carbon::now();
            $client->save();

            Session::flash('message', 'Client is archived successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/client-history?clientId=' . $client->id);
        }
    }

    /**
     * Archive Client
     */
    public function unArchiveClient(Request $request, $clientId)
    {
        $client = Client::find($clientId);

        if( !$client )
            abort(404);

        if( $request->isMethod('get') ) {
            return view('manage-client.unarchive-client', [
                'currentSection'    => 'client-list',
                'client' => $client
            ]);
        }
        else {

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'unarchive client',
                'message'   => 'Archive client : ' . $client->name,
                'ref'       => $client->id
            ]);

            $client->archived = false;
            $client->save();

            Session::flash('message', 'Client is Re-enabled successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/client-history?clientId=' . $client->id);
        }
    }

    /**
     * Manally sync client
     */
    public function selectApiClient(Request $request)
    {
        $client = Client::find($request->input('clientId'));
        if( is_null($client) )
            return response()->json([
                'status'    => 'error'
            ]);
        $apiClientId = $request->input('apiClientId');
        $apiClient = AngelInvoiceHelper::getClient($apiClientId);
        $prices = AngelInvoiceHelper::getPrices($apiClientId);

        $client->api_id                     = $apiClient['id'];
        $client->name                       = $apiClient['name'];
        $client->synced_at                  = Carbon::now();
        $client->api_updated_at = Carbon::createFromTimestamp($apiClient['updated_at']);
        $client->save();

        foreach (AngelInvoice::crmProductKeys() as $crmProductKey) {
            $client->saveProduct($crmProductKey, $prices[$crmProductKey]['price'] ?? 0);
            
            if ($crmProductKey == AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT) {
                $client->updateWebsitesProducts($crmProductKey, ['value' => $prices[$crmProductKey]['invoiceFound'] ? 0 : -2]);
            }
        }

        Session::flash('message', 'Client ' . $client->name . ' manually selected successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'   => 'success'
        ]);
    }

    /**
     * Sync All Clients
     */
    public function addApiClients(Request $request)
    {
        $apiClientIds = $request->input('apiClientIds');
        foreach( $apiClientIds as $apiClientId ) {
            $apiClient = AngelInvoiceHelper::getClient($apiClientId);
            $prices = AngelInvoiceHelper::getPrices($apiClientId);
            $client = new Client([
                'name'                      => $apiClient['name'],
                'api_id'                    => $apiClient['id'],
                'synced_at'                 => Carbon::now(),
                'api_updated_at'            => Carbon::createFromTimestamp($apiClient['updated_at'])
            ]);
            $client->save();

            // Save products
            foreach (AngelInvoice::crmProductKeys() as $crmProductKey) {

                $client->saveProduct($crmProductKey, $prices[$crmProductKey]['price'] ?? 0);
                
                if ($crmProductKey == AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT) {
                    $client->updateWebsitesProducts($crmProductKey, ['value' => $prices[$crmProductKey]['invoiceFound'] ? 0 : -2]);
                }
            }

            // Sync Websites
            if( isset($apiClient['website']) && strlen($apiClient['website']) > 0 ) {
                Website::create([
                    'name'              => $apiClient['name'],
                    'website'           => AngelInvoiceHelper::getCleanUrl($apiClient['website']),
                    'frequency'         => 'monthly',
                    'start_date'        => (Carbon::now())->startOfMonth(),
                    'client_id'         => $client->id,
                    'is_blog_client'    => false
                ]);
            }

            // $client->updateWebsitesFeeValue('yext', $prices['yext']['invoiceFound'] ? 0 : -2);
        }

        Session::flash('message', 'Selected clients added successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Sync all client infos
     */
    public function syncAllClientInfo(Request $request)
    {
        $invoices = AngelInvoiceHelper::getInvoices();
        $recurringInvoices = [];
        foreach( $invoices as $invoice ){
            if( !$invoice['is_deleted'] && $invoice['is_recurring'] ){
                $recurringInvoices[$invoice['client_id']][] = $invoice;
            }
        }

        $apiClients = AngelInvoiceHelper::getClients(true);

        $clients = Client::get();
        foreach( $clients as $client ) {
            if( !is_null($client->api_id) && $client->api_id > 0 && isset($apiClients[$client->api_id])){
                $prices = AngelInvoiceHelper::getPrices($client->api_id, $recurringInvoices[$client->api_id] ?? []);
                $client->synced_at                  = Carbon::now();
                $client->api_updated_at = Carbon::createFromTimestamp($apiClients[$client->api_id]['updated_at']);
                $client->save();

                // Sync Products
                foreach (AngelInvoice::crmProductKeys() as $crmProductKey) {

                    $client->saveProduct($crmProductKey, $prices[$crmProductKey]['price'] ?? 0);
                    
                    if ($crmProductKey == AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT) {
                        $client->updateWebsitesProducts($crmProductKey, ['value' => $prices[$crmProductKey]['invoiceFound'] ? 0 : -2]);
                    }
                }

                self::updateWebsitesForClient($client);

                // $client->updateWebsitesFeeValue('yext', $prices['yext']['invoiceFound'] ? 0 : -2);
            }
        }

        Session::flash('message', 'All clients synced from API successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Sync client Info
     */
    public function syncClientInfo(Request $request)
    {
        $client = Client::find($request->clientId);
        if( is_null($client) )
            return response()->json([
                'status'    => 'error'
            ]);
        self::syncSingleClientInfo($client);
        self::updateWebsitesForClient($client);

        Session::flash('message', 'Client ' . $client->name . ' synced from API successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Sync sigle client info
     * @param \App\Model $client
     */
    public static function syncSingleClientInfo($client)
    {
        if( !is_null($client->api_id) && $client->api_id > 0){
            $apiClient = AngelInvoiceHelper::getClient($client->api_id);
            $prices = AngelInvoiceHelper::getPrices($client->api_id);

            $client->synced_at                  = Carbon::now();
            $client->api_updated_at             = Carbon::createFromTimestamp($apiClient['updated_at']);
            $client->save();

            // Sync Products
            foreach (AngelInvoice::crmProductKeys() as $crmProductKey) {
                $client->saveProduct($crmProductKey, $prices[$crmProductKey]['price'] ?? 0);

                if ($crmProductKey == AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT) {
                    $client->updateWebsitesProducts($crmProductKey, ['value' => $prices[$crmProductKey]['invoiceFound'] ? 0 : -2]);
                }
            }

            //Sync website if this client doesn't have any website
            if( count($client->websites()->get()) == 0 ) {
                if( isset($apiClient['website']) && strlen($apiClient['website']) > 0 ) {
                    Website::create([
                        'name'              => $apiClient['name'],
                        'website'           => AngelInvoiceHelper::getCleanUrl($apiClient['website']),
                        'frequency'         => 'monthly',
                        'start_date'        => (Carbon::now())->startOfMonth(),
                        'client_id'         => $client->id,
                        'is_blog_client'    => false
                    ]);
                }
            }

            // $client->updateWebsitesFeeValue('yext', $prices['yext']['invoiceFound'] ? 0 : -2);
        }
    }

    /**
     * Update websites for client with syncing info
     * @param \App\Client $client
     */
    public static function updateWebsitesForClient($client)
    {
        $websites = $client->websites()->get();
        if( $client->g_suite > 0 &&  count($websites) == 1 ) {
            $website = $websites[0];
            $website->email = 'g-suite';
            $website->save();
        }

    }

    /**
     * Inline Editing Update attribute of client
     */
    public function updateAttribute(Request $request)
    {
        $clientId   = $request->input('pk');
        $key        = $request->input('name');
        $value      = $request->input('value');

        $website = Client::find($clientId);
        if( is_null($website) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $website->$key = $value;
        $website->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    protected function applyFilters($query)
    {
        if (! empty(request()->input('user_id')) && ! empty(request()->input('filter_type'))) {
            $query->where(request()->input('filter_type'), request()->input('user_id'));
        }

        return $query;
    }
}
