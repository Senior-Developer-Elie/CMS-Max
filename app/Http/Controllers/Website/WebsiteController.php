<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Website;
use App\User;
use App\AdminHistory;
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

        // Prepare blog industries
        $blogIndustries = array_map(function($blogIndustry){
            return [
                'value' => $blogIndustry['id'],
                'text'  => $blogIndustry['name']
            ];
        }, BlogIndustry::orderBy('name')->get()->toArray());

        $this->data['currentSection']           = 'website-list';
        $this->data['initialExpandOnHover']     = true;
        $this->data['websites']                 = Website::where('archived', 0)->get();
        $this->data['archivedWebsites']         = Website::where('archived', 1)->get();
        $this->data['blogIndustries']           = BlogIndustry::orderBy('name')->get();
        $this->data['allIndustries']            = $blogIndustries;
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
        $this->data['admins'] = User::orderBy('name')->get();
        $this->data['clients'] = Client::orderBy('name')->get();

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
        $this->data['admins'] = User::orderBy('name')->get();
        $this->data['clients'] = Client::orderBy('name')->get();

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

        Session::flash('message', 'Website updated successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('websites.index');
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
        $this->data['shippingMethodTypes'] = WebsiteHelper::getShippingMethodTypes();
        $this->data['yextTypes'] = WebsiteHelper::getYextTypes();
        $this->data['blogFrequencies'] = WebsiteHelper::getBlogFrequencies();
    }
}
