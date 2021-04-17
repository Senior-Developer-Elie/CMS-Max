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

        $socialMediaStages = SocialMediaStage::orderBy('order')
            ->with('websites.socialMediaCheckLists')
            ->get();

        return view('manage-website.social-media.index', [
            'currentSection' => 'social-media',
            'socialMediaStages' => $socialMediaStages,
            'socialMediaCheckLists' => \App\WebsiteSocialMediaCheckList::socialMediaCheckLists(),
        ]);
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
}
