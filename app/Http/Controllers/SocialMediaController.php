<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Website;
use App\SocialMediaStage;
use App\WebsiteSocialMediaCheckList;

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

    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Social Media') )
            return redirect('/webadmin');
        
        $this->data['currentSection'] = 'social-media';
        if (empty($statusFilter = $request->input('status_filter'))) {
            $statusFilter = 'active';
        }
        $this->data['statusFilter'] = $statusFilter;

        if ($statusFilter == 'active') {
            $this->data['socialMediaStages'] = SocialMediaStage::orderBy('order')
                ->with('websites.socialMediaCheckLists')
                ->get();
            $this->data['socialMediaCheckLists'] = \App\WebsiteSocialMediaCheckList::socialMediaCheckLists();
        } else {
            $this->data['websites'] = Website::where('archived', 0)
                ->where('social_media_archived', 1)
                ->orderBy('name')
                ->get();
        }

        return view('manage-website.social-media.index', $this->data);
    }


    public function show(Request $request, $websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $website->client = $website->client();
        $website->socialMediaCheckLists = $website->socialMediaCheckLists()->get()->toArray();

        return response()->json([
            'status' => 'success',
            'website' => $website->toArray(),
        ]);
    }

    public function updateSocialMediaChecklist(Request $request, $websiteId)
    {
        $website = Website::findOrFail($websiteId);

        $value = $request->input('value') == 'on' ? true : false;
        $socialMediaKey = $request->input('social_media_key');

        if ($value) {
            $websiteSocialMediaCheckList = WebsiteSocialMediaCheckList::updateOrCreate([
                'website_id' => $website->id,
                'key' => $socialMediaKey,
            ], [
                'completed_at' => Carbon::now(),
                'user_id' => Auth::user()->id,
            ]);
        } else {
            WebsiteSocialMediaCheckList::where('website_id', $website->id)
                ->where('key', $socialMediaKey)
                ->delete();
        }

        $response = [
            'status' => 'success',
        ];

        if ($value) {
            $response['websiteSocialMediaCheckList'] = $websiteSocialMediaCheckList->toArray();
        }

        return response()->json($response);
    }

    public function updateSocialMediaArchived(Request $request, $websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $socialMediaArchived = $request->input('value') == 'archived';

        $website->social_media_archived = $socialMediaArchived;
        if (! $socialMediaArchived) {
            $website->social_media_stage_id = SocialMediaStage::first()->id;
        }
        $website->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
