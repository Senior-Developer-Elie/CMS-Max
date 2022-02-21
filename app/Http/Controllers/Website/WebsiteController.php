<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Website;
use App\User;
use App\AdminHistory;
use App\AngelInvoice;
use App\BlogIndustry;
use App\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Helpers\WebsiteHelper;
use App\Sanitizers\WebsiteSanitizer;
use App\Validators\WebsiteValidator;

class WebsiteController extends Controller
{
    protected $data = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Website List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Websites') )
            return redirect('/webadmin');

        $this->data = [];

        $this->data['blogIndustries']          = $this->getBlogIndustriesForFilter();
        $this->data['blogIndustriesForInline'] = array_map(function($blogIndustry){
            return [
                'value' => $blogIndustry['id'],
                'text'  => $blogIndustry['name']
            ];
        }, $this->data['blogIndustries']->toArray());

        $this->data['currentSection']           = 'website-list';
        $this->data['initialExpandOnHover']     = true;
        $this->data['websites']                 = $this->getActiveWebsites();
        $this->data['archivedWebsites']         = Website::where('archived', 1)->get();
        $this->data['blogIndustries']           = $this->getBlogIndustriesForFilter();
        $this->data['admins']                   = User::get();

        $this->prepareWebsiteAttributes();

        return view('websites.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->prepareWebsiteAttributes();
        $this->data['blogIndustries'] = BlogIndustry::orderBy('name')->get();
        $this->data['admins'] = User::orderBy('name')
            ->where('type', '!=', User::USER_TYPE_CMS_MAX_DEVELOPER)
            ->get();
        $this->data['clients'] = Client::orderBy('name')->get();

        $this->data['websiteProducts'] = Website::getDefaultProducts();

        return view('websites.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(Request $request)
    {
        // Sanitize
        $data = (new WebsiteSanitizer)->sanitize($request->all());

        // Validate
        $validator = new WebsiteValidator();
        if (! $validator->validate($data, 'create')) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        $website = Website::create($data);

        $this->updateWebsiteApiProducts($website, $data);

        Session::flash('message', 'Website created successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.edit', [$website->id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Website $website
     * 
     * @return Response
     */
    public function edit(Website $website)
    {
        $this->prepareWebsiteAttributes();
        
        $this->data['website'] = $website;
        $this->data['blogIndustries'] = BlogIndustry::orderBy('name')->get();
        $this->data['admins'] = User::orderBy('name')
            ->where('type', '!=', User::USER_TYPE_CMS_MAX_DEVELOPER)
            ->get();
        $this->data['clients'] = Client::orderBy('name')->get();
        $this->data['websiteProducts'] = $website->getProductsWithDefault();
        $this->data['products'] = AngelInvoice::products();

        $this->prepareBudgetProducts($website);

        return view('websites.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $product
     * @return Response
     */
    public function update(Website $website, Request $request)
    {
        // Sanitize
        $data = (new WebsiteSanitizer)->sanitize($request->all());
        
        // Validate
        $validator = new WebsiteValidator();
        if (! $validator->validate($data, 'update')) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        $website->fill($data);
        $website->save();

        $this->updateWebsiteApiProducts($website, $data);

        Session::flash('message', 'Website updated successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.edit', [$website->id]);
    }

    /**
     * Show the page for confirming delete
     *
     * @param $websiteId
     * @return \Illuminate\View\View
     */
    public function confirmDelete($websiteId)
    {
        if (! $website = Website::find($websiteId)) {
            abort(404);
        }

        $this->data['website'] = $website;

        return view('websites.confirm-delete', $this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $website
     * @return Response
     */
    public function destroy($websiteId)
    {
        $website = Website::findOrFail($websiteId);

        $website->delete();

        Session::flash('message', 'Website deleted successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.index');
    }

    /**
     * Archive website
     *
     * @param $websiteId
     * @return Response
     */
    public function archive($websiteId)
    {
        $website = Website::findOrFail($websiteId);

        $website->archived = 1;
        $website->archived_at = Carbon::now();
        $website->save();

        Session::flash('message', 'Website archived successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.edit', [$website->id]);
    }

    /**
     * Restore website
     *
     * @param $websiteId
     * @return Response
     */
    public function restore($websiteId)
    {
        $website = Website::findOrFail($websiteId);

        $website->archived = 0;
        $website->save();

        Session::flash('message', 'Website restored successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.edit', [$website->id]);
    }

    /**
     * Inline Editing Update attribute of website
     */
    public function updateAttribute(Request $request)
    {
        $websiteId  = $request->input('pk');
        $key        = $request->input('name');
        $value      = $request->input('value');

        $website = Website::find($websiteId);
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

    /**
     * Inline Editing Update for website product value
     */
    public function updateProductValue(Request $request)
    {
        $websiteId  = $request->input('pk');
        $key        = $request->input('name');
        $value      = $request->input('value');

        $website = Website::find($websiteId);
        if( is_null($website) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        
        if (in_array(intval($value), [-1, -2, -3])) {
            $website->saveProduct($key, [
                'value' => $value
            ]);
        }

        return response()->json([
            'status'    => 'success'
        ]);
    }

    protected function prepareWebsiteAttributes()
    {
        $this->data['websiteTypes'] = WebsiteHelper::getAllWebsiteTypes();
        $this->data['affiliateTypes'] = WebsiteHelper::getAllWebsiteAffiliates();
        $this->data['dnsTypes'] = WebsiteHelper::getAllWebsiteDNS();
        $this->data['paymentGateways'] = WebsiteHelper::getAllPaymentGateways();
        $this->data['emailTypes'] = WebsiteHelper::getAllEmailTypes();
        $this->data['sitemapTypes'] = WebsiteHelper::getAllSitemapTypes();
        $this->data['leftReviewTypes'] = WebsiteHelper::getAllLeftReviewTypes();
        $this->data['portfolioTypes'] = WebsiteHelper::getOnPortfolioTypes();
        $this->data['yextTypes'] = WebsiteHelper::getYextTypes();
        $this->data['blogFrequencies'] = WebsiteHelper::getBlogFrequencies();
    }

    protected function updateWebsiteApiProducts(Website $website, array $data)
    {
        $syncedWebsiteApiProductIds = [];
        
        foreach ($data['website_products'] as $crmProductKey => $websiteProduct) {
            $websiteApiProduct = $website->saveProduct($crmProductKey, [
                'value' => $websiteProduct['value'],
                'frequency' => $websiteProduct['frequency']
            ]);

            $syncedWebsiteApiProductIds[] = $websiteApiProduct->id;
        }

        $website->apiProducts()
            ->whereNotIn('id', $syncedWebsiteApiProductIds)
            ->delete();
    }

    protected function prepareBudgetProducts(Website $website)
    {
        $budgetProducts = collect();
        $totalBudget = 0;

        $websiteService = $website->getProductValues(\App\AngelInvoice::crmProductKeys());

        foreach (AngelInvoice::products() as $crmProductKey => $apiProductKey) {
            if (($websiteService[$crmProductKey] ?? 0) > 0) {
                $budgetProducts[] = (object)[
                    'value' => $websiteService[$crmProductKey],
                    'name' => $apiProductKey,
                ];

                $totalBudget += $websiteService[$crmProductKey];
            }
        }
        $this->data['budgetProducts'] = $budgetProducts;
        $this->data['totalBudget'] = $totalBudget;
    }
    
    protected function getActiveWebsites()
    {
        $query = Website::where('archived', 0);

        if (! empty(request()->input('blog_industry_id'))) {
            $query->where('blog_industry_id', request()->input('blog_industry_id'));
        }

        if (! empty(request()->input('affilliate_id'))) {
            $query->where('affiliate', request()->input('affilliate_id'));
        }

        if (! empty(request()->input('website_type'))) {
            $query->where('type', request()->input('website_type'));
        }

        if (! empty($syncStatus = request()->input('sync_status'))) {
            if ($syncStatus == 'synced') {
                $query->where('sync_from_client', 1);
            } else {
                $query->where('sync_from_client', 0);
            }
        }

        return $query->get();
    }

    protected function getBlogIndustriesForFilter()
    {
        return BlogIndustry::orderBy('name')
            ->with('websites')
            ->get()
            ->map(function($blogIndustry) {
                $blogIndustry->active_websites_count = $blogIndustry->websites->filter(function($website) {
                    return ! $website->archived;
                })->count();

                return $blogIndustry;
            });
    }
}
