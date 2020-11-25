<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Website;
use App\Http\Controllers\Controller;

class WebsiteManageSenderController extends Controller
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
     * Manage Website Sender Name
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage Website Sender') )
            return redirect('/webadmin');

        $activeWebsites = Website::where('archived', 0)->orderBy('name')->get();
        $archivedWebsites = Website::where('archived', 1)->orderBy('name')->get();

        return view('manage-website.manage-sender', [
            'currentSection'    => 'manage-sender',
            'activeWebsites'    => $activeWebsites,
            'archivedWebsites'  => $archivedWebsites
        ]);
    }
}
