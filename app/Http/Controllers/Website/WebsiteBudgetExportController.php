<?php

namespace App\Http\Controllers\Website;

use App\AngelInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use App\Exports\WebsiteBudgetExport;
use App\Http\Controllers\Controller;
use App\Website;

class WebsiteBudgetExportController extends Controller
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
        if( !Auth::user()->hasPagePermission('Websites') )
            return redirect('/webadmin');

        return (new WebsiteBudgetExport($this->getWebsitesWithBudget()))
            ->download("Websites Budget.csv", Excel::CSV);
    }

    protected function getWebsitesWithBudget()
    {
        $websites = Website::orderBy('name')->where('archived', '!=', 1)->get();

        return $websites->map(function($website) {
            $totalBudget = 0;

            $websiteService = $website->getProductValues(\App\AngelInvoice::crmProductKeys());

            foreach (AngelInvoice::products() as $crmProductKey => $apiProductKey) {
                if (($websiteService[$crmProductKey] ?? 0) > 0) {
                    $totalBudget += $websiteService[$crmProductKey];
                }
            }

            return (object)[
                'name' => $website->name,
                'website' => $website->website,
                'total_budget' => $totalBudget
            ];
        });
    }
}
