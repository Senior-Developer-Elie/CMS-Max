<?php

namespace App\Http\Controllers;

use App\AngelInvoice;
use Illuminate\Http\Request;

use App\Website;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Helpers\WebsiteHelper;
class SocialMediaController extends Controller
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
     * Payroll Websites List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Social Media') )
            return redirect('/webadmin');

        $query = Website::where('archived', 0);
        $this->applyFilters($query);
        
        $activeWebsites = $query->get();

        // Attach plan
        $activeWebsites->map(function($website) {

            $website->plan = null;
            
            foreach (AngelInvoice::SOCIAL_PLANS_CRM_PRODUCT_KEYS as $crmProductKey) {
                if ($website->getProductValue($crmProductKey) > 0) {
                    $website->plan = $crmProductKey;
                    $website->planName = AngelInvoice::products()[$crmProductKey];
                    break;
                }
            }

            return $website;
        });

        return view('manage-website.social-media-list', [
            'currentSection'        => 'social-media',
            'activeWebsites'        => $activeWebsites,
        ]);
    }

    /**
     * Archive Website
     */
    public function archiveWebsite(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        $website->social_media_archived = true;
        $website->save();

        Session::flash('message', 'Website is archived Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive Website
     */
    public function unarchiveWebsite(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        $website->social_media_archived = false;
        $website->save();

        Session::flash('message', 'Website is Re-enabled Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    protected function applyFilters($query)
    {
        if (request()->input('show_clients_only') == 'on') {
            $query->whereRaw('social_ad_spend + social_management_fee > 0');
        }
    }
}
