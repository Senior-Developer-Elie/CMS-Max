<?php

namespace App\Http\Controllers;

use App\AngelInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Website;
use App\User;
use App\BlogIndustry;
use App\Http\Helpers\WebsiteHelper;

class WebsiteBudgetingController extends Controller
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

    /**
     * Budgeting Websites List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Budgeting') )
            return redirect('/webadmin');

        $filterType = empty($request->input('filterType')) ? 'all' : $request->input('filterType');

        if( $filterType == 'unsynced' )
            $websites = Website::where('sync_from_client', 0)->where('archived', 0)->orderBy('name')->get();
        else
            $websites = Website::where('archived', 0)->orderBy('name')->get();

        $blogIndustries = BlogIndustry::orderBy('name')->get();
        $prettyBlogIndustries = [];
        foreach( $blogIndustries as $industry ){
            $prettyBlogIndustries[] = [
                'value' => $industry->id,
                'text'  => $industry->name
            ];
        }
        return view('manage-website.budgeting-list', [
            'currentSection'            => 'budgeting',
            'websites'                  => $websites,
            'filterType'                => $filterType,
            'allWebsiteTypes'           => WebsiteHelper::getAllWebsiteTypes(),
            'allAffiliateTypes'         => WebsiteHelper::getAllWebsiteAffiliates(),
            'allDNSTypes'               => WebsiteHelper::getAllWebsiteDNS(),
            'allPaymentGateways'        => WebsiteHelper::getAllPaymentGateways(),
            'allEmailTypes'             => WebsiteHelper::getAllEmailTypes(),
            'allSitemapTypes'           => WebsiteHelper::getAllSitemapTypes(),
            'allLeftReviewTypes'        => WebsiteHelper::getAllLeftReviewTypes(),
            'allPortfolioTypes'         => WebsiteHelper::getOnPortfolioTypes(),
            'allShippingMethodTypes'    => WebsiteHelper::getShippingMethodTypes(),
            'allYextTypes'              => WebsiteHelper::getYextTypes(),
            'blogIndustries'            => BlogIndustry::orderBy('name')->get(),
            'allIndustries'             => $prettyBlogIndustries,
            'admins'                    => User::get(),
            'apiProductsFields'         => AngelInvoice::products()
        ]);
    }
}
