<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Website;
use App\User;
use App\AdminHistory;
use App\BlogIndustry;
use App\PaymentGateway;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Helpers\WebsiteHelper;
class WebsiteController extends Controller
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
     * Website List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Websites') )
            return redirect('/webadmin');

        $blogIndustries = BlogIndustry::orderBy('name')->get();
        $prettyBlogIndustries = [];
        foreach( $blogIndustries as $industry ){
            $prettyBlogIndustries[] = [
                'value' => $industry->id,
                'text'  => $industry->name
            ];
        }

        return view('manage-website.website-list', [
            'currentSection'            => 'website-list',
            'websites'                  => Website::where('archived', 0)->get(),
            'archivedWebsites'          => Website::where('archived', 1)->get(),
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
            'initialExpandOnHover'      => true
        ]);
    }

    /**
     * Get Website Info
     */
    public function getWebsiteInfo(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);

        $data = $website->toArray();
        $data['start_date'] = (new Carbon($website->start_date))->format('m/Y');

        if( !is_null($website->client()) && $website->client()->archived )
            $data['client_archived'] = true;
        else
            $data['client_archived'] = false;

        return response()->json([
            'status'    => 'success',
            'data'      => $data
        ]);
    }

    /**
     * Add Website
     */
    public function addWebsite(Request $request)
    {
        $websiteId = $request->input('websiteId');
        $data = $request->all();

        // is blog client data
        if( isset($data['is_blog_client']) && $data['is_blog_client'] == 'on')
            $data['is_blog_client'] = true;
        else
            $data['is_blog_client'] = false;

        // Chargebee
        if( isset($data['chargebee']) && $data['chargebee'] == 'on')
            $data['chargebee'] = true;
        else
            $data['chargebee'] = false;

        // sync from client data
        if( isset($data['sync_from_client']) && $data['sync_from_client'] == 'on')
            $data['sync_from_client'] = true;
        else
            $data['sync_from_client'] = false;

        // Change -1 if manual value is set n/a
        $data['service'] = $data['service'] == 'n/a' ? -1 : $data['service'];
        $data['yext'] = $data['yext'];
        if( $data['yext'] == 'n/a' || $data['yext'] == 'not-needed' )
            $data['yext'] = -1;
        else if( $data['yext'] == 'need-to-sell' )
            $data['yext'] = -3;
        else if( $data['yext'] == 'not-interested' )
            $data['yext'] = -4;
        $data['g_suite'] = $data['g_suite'] == 'n/a' ? -1 : $data['g_suite'];
        $data['hosting'] = $data['hosting'] == 'n/a' ? -1 : $data['hosting'];
        $data['ssl'] = $data['ssl'] == 'n/a' ? -1 : $data['ssl'];
        $data['googleAds'] = $data['googleAds'] == 'n/a' ? -1 : $data['googleAds'];
        $data['googleManagementFee'] = $data['googleManagementFee'] == 'n/a' ? -1 : $data['googleManagementFee'];
        $data['support_maintenance'] = $data['support_maintenance'] == 'n/a' ? -1 : $data['support_maintenance'];
        $data['internet_marketing'] = $data['internet_marketing'] == 'n/a' ? -1 : $data['internet_marketing'];
        $data['cmsmax_software'] = $data['cmsmax_software'] == 'n/a' ? -1 : $data['cmsmax_software'];
        $data['cmsmax_ecommerce_software'] = $data['cmsmax_ecommerce_software'] == 'n/a' ? -1 : $data['cmsmax_ecommerce_software'];
        $data['social_media_management'] = $data['social_media_management'] == 'n/a' ? -1 : $data['social_media_management'];
        $data['domain'] = $data['domain'] == 'n/a' ? -1 : $data['domain'];
        $data['dont_go'] = $data['dont_go'] == 'n/a' ? -1 : $data['dont_go'];
        $data['order_snapp'] = $data['order_snapp'] == 'n/a' ? -1 : $data['order_snapp'];
        $data['cms_max_plus'] = $data['cms_max_plus'] == 'n/a' ? -1 : $data['cms_max_plus'];
        $data['cms_max_ecommerce_plus'] = $data['cms_max_ecommerce_plus'] == 'n/a' ? -1 : $data['cms_max_ecommerce_plus'];

        $data['start_date'] = self::getCarbonFromYearMonth($data['start_date']);

        if( $data['completed_at'] == null || $data['completed_at'] == '' || $data['completed_at'] == 'null' )
            $data['completed_at'] = null;

        if( $websiteId == "-1" ) {
            $website = new Website($data);
            $website->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'add website',
                'message'   => 'Add website : ' . $website->name,
                'ref'       => $website->id
            ]);

            Session::flash('message', 'Website added Successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        else {
            $website = Website::find($websiteId);
            if( is_null($website) ){
                return resposne()->json([
                    'status'    => 'error'
                ]);
            }
            $website->fill($data);
            $website->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'edit website',
                'message'   => 'Edit website : ' . $website->name,
                'ref'       => $website->id
            ]);

            Session::flash('message', 'Website updated Successfully!');
            Session::flash('alert-class', 'alert-success');
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $website->toArray()
        ]);
    }

    /**
     * Delete Website
     */
    public function deleteWebsite(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        AdminHistory::addHistory([
            'user_id'   => Auth::user()->id,
            'type'      => 'delete website',
            'message'   => 'Delete website : ' . $website->name,
            'ref'       => $website->id
        ]);
        $website->delete();

        Session::flash('message', 'Website Deleted Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
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
        AdminHistory::addHistory([
            'user_id'   => Auth::user()->id,
            'type'      => 'archive website',
            'message'   => 'Archive website : ' . $website->name,
            'ref'       => $website->id
        ]);
        $website->archived = true;
        $website->archived_at = Carbon::now();
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
        AdminHistory::addHistory([
            'user_id'   => Auth::user()->id,
            'type'      => 'unarchive website',
            'message'   => 'Re-enable website : ' . $website->name,
            'ref'       => $website->id
        ]);
        $website->archived = false;
        $website->save();

        Session::flash('message', 'Website is Re-enabled Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
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
     * Get Carbon object from month and year
     */
    public static function getCarbonFromYearMonth($dateStr)
    {
        $dateMonthArray = explode('/', $dateStr);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];

        return (Carbon::createFromDate($year, $month, 1))->startOfMonth();
    }
}
