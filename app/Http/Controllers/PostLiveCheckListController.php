<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\WebsiteHelper;

class PostLiveCheckListController extends Controller
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
        if( !Auth::user()->hasPagePermission('Post-Live Checklist') )
            return redirect('/webadmin');

        $websites = Website::orderBy('name')->get();
        $activeWebsites = [];
        $completedWebsites = [];
        $archivedWebsites = [];

        foreach( $websites as $website ){
            if ($website->archived) continue;
            
            if( $website->post_live_check_archived || $website->completedPostWebsite() ) {
                $completedWebsites[] = $website;
            }
            else {
                $activeWebsites[] = $website;
            }
        }
        return view('manage-website.post-check-list', [
            'currentSection'        => 'post-live-checklist',
            'activeWebsites'        => $activeWebsites,
            'archivedWebsites'      => $archivedWebsites,
            'completedWebsites'     => $completedWebsites,
            'allPostLiveOptions'    => WebsiteHelper::getAllPostLiveOptions()
        ]);
    }

    /**
     * Archive Website
     */
    public function archive(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        
        $website->post_live_check_archived = true;
        
        $website->save();

        Session::flash('message', 'Website is mark as completed successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }
}
