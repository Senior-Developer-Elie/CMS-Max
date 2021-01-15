<?php

namespace App\Http\Controllers\Website;

use App\AngelInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Website;
use App\User;
use App\BlogIndustry;
use App\Http\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;

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

        return view('manage-website.budgeting-list', [
            'currentSection'            => 'budgeting',
            'websites'                  => $websites,
            'filterType'                => $filterType,
            'apiProductsFields'         => AngelInvoice::products()
        ]);
    }
}
