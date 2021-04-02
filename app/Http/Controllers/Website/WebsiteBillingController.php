<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\WebsiteHelper;
use App\Task;
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
                $query->whereNotIn('client_id', [1, 7, 64, 158, 97, 143, 159, 103, 14, 177, 251, 159, 28]);
                $query->orWhereIn('id', [1, 279, 150, 388, 327, 100, 13, 169, 342]);
            })
            ->whereNotIn('id', [318, 361, 99, 164, 168, 173, 256, 18, 460, 359, 119, 122, 373, 332, 193])
            ->where('type', '!=', 'redirect-website')
            ->get();
        
        $this->data['websites'] = $this->data['websites']->filter(function($website) {
            return Task::where('website_id', $website->id)->count() == 0;
        });
        $this->data['billingTypes'] = WebsiteHelper::getAllBillingtypes();

        return view('manage-website.billing-list', $this->data);
    }
}
