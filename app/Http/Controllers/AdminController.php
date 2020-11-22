<?php

namespace App\Http\Controllers;

use App\InnerBlog;
use App\Blog;
use App\BlogIndustry;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use DB;

use App\Http\Helpers\NotificationHelper;
use App\Http\Helpers\JobHelper;
use App\Http\Helpers\WebsiteHelper;

class AdminController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $notifications = NotificationHelper::getNotificationsForUser(Auth::user()->id);
        $prettyNotifications = [];

        foreach( $notifications as $notification ) {
            $prettyNotification = [
                'notification'      => $notification,
                'icon'              => NotificationHelper::getIcon($notification),
                'text'              => NotificationHelper::getText($notification, true),
                'projectType'       => NotificationHelper::getSectionType($notification),
                'targetLink'        => NotificationHelper::getTargetTextLink($notification),
                'targetText'        => NotificationHelper::getTargetText($notification),
            ];
            if( $notification->type == 'complete job' ) {
                $prettyNotification['innerBlog'] = InnerBlog::find($notification->reference_id);
            }
            else if( $notification->type == 'complete blog' ) {
                $prettyNotification['blog'] = Blog::find($notification->reference_id);
            }
            $prettyNotifications[] = $prettyNotification;
        }


        $blogIndustries = BlogIndustry::orderBy('name')->get();
        $prettyBlogIndustries = [];
        foreach( $blogIndustries as $industry ){
            $prettyBlogIndustries[] = [
                'value' => $industry->id,
                'text'  => $industry->name
            ];
        }

        return view("dashboard", [
            'currentSection'            => 'dashboard',
            'notifications'             => $prettyNotifications,
            'upcomingJobs'              => JobHelper::getUpcomingJobsForUser(),
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
        ]);

    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        if( $request->isMethod('get') )
        {
            return view('auth.password');
        }
        else {

            Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ])->validate();

            \Auth::user()->password = Hash::make($request['password']);
            \Auth::user()->save();
            return redirect('/webadmin');
        }
    }

    /**
     * Database seed
     */
    public function dbSeed(Request $request)
    {
        $histories = \App\ProfitLossHistory::all();
        foreach ($histories as $history) {
            $data = $history->data;
            foreach( $data['profits'] as $index => $profit ) {
                $data['profits'][$index]['name'] = str_replace('G Suite', 'Google Workspace', $data['profits'][$index]['name']);
            }
            foreach( $data['expenses'] as $index => $expense ) {
                $data['expenses'][$index]['name'] = str_replace('G Suite', 'Google Workspace', $data['expenses'][$index]['name']);
            }
           $history->data = $data;
           $history->save();
        }
    }

    /**
     * Remove Temporary uploaded inner blog files
     */
    public function removeUploadedFiles(Request $request)
    {
        DB::delete('DELETE FROM inner_blog_files WHERE inner_blog_id = -1 AND created_at < DATE_ADD(NOW(), INTERVAL -12 HOUR)');
    }

    /**
     * Laraform
     */
    public function laraform(Request $request)
    {
        return view('laraform');
    }

    /**
     * Get Notifications API
     */
    public function getNotifications(Request $request)
    {
        list($notifications, $actionNotifications) = get_global_notifications();

        return response()
        ->json([
            'status'                => 'success',
            'notifications'         => $notifications,
            'actionNotifications'   => $actionNotifications,
        ]);
    }
}
