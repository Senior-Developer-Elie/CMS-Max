<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Task;
use App\Website;
use Auth;

use App\Http\Helpers\WebsiteStatisticsHelper;
class WebsitesStatisticsController extends Controller
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
     * Website Statistics
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('History & Stats') )
            return redirect('/webadmin');

        $completedTasks = Task::where('stage_id', 10)
            ->orderBy('name')
            ->whereNull('website_id')
            ->get();
        $liveWebsites = Website::where('type', '!=', 'no-website')
            ->where('type', '!=', 'redirect-website')
            ->orderBy('name')
            ->notArchived()
            ->get();
        $noWebsites = Website::where('type', 'no-website')
            ->orderBy('name')
            ->notArchived()
            ->get();
        $redirectWebsites = Website::where('type', 'redirect-website')
            ->orderBy('name')
            ->notArchived()
            ->get();

        return view('website-statistics.index', [
            'currentSection'    => 'website-completed',
            'completedTasks'    => $completedTasks,
            'liveWebsites'      => $liveWebsites,
            'noWebsites'        => $noWebsites,
            'redirectWebsites'  => $redirectWebsites
        ]);
    }

    /**
     * Get Website Completion Data for Barchart
     */
    public function getWebsiteCompletionStatusForBarChart(Request $request)
    {
        list($chartData, $yearlyChartData) = WebsiteStatisticsHelper::getCompletionStatistics();
        return response()->json([
            'status'            => 'success',
            'chartData'         => $chartData,
            'yearlyChartData'   => $yearlyChartData
        ]);
    }
}
