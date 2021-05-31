<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Website;
use Illuminate\Support\Facades\Auth;

class WebsiteMarketingController extends Controller
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
     * Marketing Websites List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Marketing') )
            return redirect('/webadmin');

        $filterStatus = empty($request->input('filterStatus')) ? 'on' : $request->input('filterStatus');

        $websites = Website::orderBy('name')->get();
        $marketingWebsites = [];

        foreach( $websites as $website ){
            if ($website->type != 'redirect-website' && !$website->archived ){

                if( $filterStatus == 'on' ){
                    if( $website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_SPEND) >= 1
                        OR $website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_MANAGEMENT) >= 1 
                        OR $website->getProductValue(\App\AngelInvoice::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM) >= 1)
                        $marketingWebsites[] = $website;
                }
                else{
                    $marketingWebsites[] = $website;
                }
            }
        }

        return view('manage-website.marketing-list', [
            'currentSection'        => 'marketing',
            'websites'              => $marketingWebsites,
            'filterStatus'         => $filterStatus
        ]);
    }
}
