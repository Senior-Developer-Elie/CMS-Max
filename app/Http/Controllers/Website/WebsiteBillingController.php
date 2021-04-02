<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\WebsiteHelper;
use App\Website;

class WebsiteBillingController extends Controller
{
    protected $data = [];

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
        if( !Auth::user()->hasPagePermission('Billing') )
            return redirect('/webadmin');

        $this->data['websites'] = Website::where('archived', 0)
            ->where('type', '!=', 'no-website')
            ->orderBy('website')
            ->where(function($query) {
                $query->whereNotIn('client_id', [7, 64, 158, 97, 143, 159, 103, 14, 177]);
                $query->orWhereIn('website_id', [279, 150, 388, 326, 327, 100, 13, 169]);
            })
            ->get();
        $this->data['billingTypes'] = WebsiteHelper::getAllBillingtypes();

        return view('manage-website.billing-list', $this->data);
    }
}
