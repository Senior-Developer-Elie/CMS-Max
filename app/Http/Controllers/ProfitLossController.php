<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Helpers\BlogHelper;
use App\Website;
use App\Expense;
use App\Profit;
use App\ProfitLossHistory;

use Auth;
use Session;
use Carbon\Carbon;

class ProfitLossController extends Controller
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
        if( !Auth::user()->hasPagePermission('Profit & Loss') )
            return redirect('/webadmin');

        $targetHistory = is_null($request->input('targetHistory')) ? "-1" : $request->input('targetHistory');
        if( $targetHistory == "-1" ) {
            list($profits, $expenses, $totalProfit) = self::getProfitsAndExpenses();
            $blogCount = BlogHelper::getAvailableBlogCountThisMonth();
            $currentMonth = Carbon::now()->format('M Y');
        }
        else {
            $profitLossHistory = ProfitLossHistory::find($targetHistory);
            $profits = $profitLossHistory->data['profits'];
            $expenses = $profitLossHistory->data['expenses'];
            $totalProfit = $profitLossHistory->data['totalProfit'];
            $blogCount = $profitLossHistory->data['blogCount'];
            $currentMonth = (new Carbon($profitLossHistory->desired_date))->format('M Y');
        }

        //Get Histories DAta
        $histories = ProfitLossHistory::orderBy('desired_date')->get()->toArray();
        $totalProfitNames = [];
        $totalExpenseNames = [];
        foreach( $histories as $index => $historyData ) {
            $totalExpense = 0;
            foreach( $historyData['data']['expenses'] as $expense ) {
                if( $expense['key'] == 'blog' ) {
                    $totalExpense += floatval($expense['price']) * $historyData['data']['blogCount'];
                    $histories[$index]['data']['totalBlogExpense'] = floatval($expense['price']) * $historyData['data']['blogCount'];
                }
                else {
                    $totalExpense += floatval($expense['price']);
                }
            }
            $histories[$index]['data']['totalExpense'] = $totalExpense;

            $totalProfitNames = array_unique(array_merge($totalProfitNames, array_column($historyData['data']['profits'], 'name')), SORT_REGULAR);
            $totalExpenseNames = array_unique(array_merge($totalExpenseNames, array_column($historyData['data']['expenses'], 'name')), SORT_REGULAR);
        }

        $totalProfitNames = array_diff($totalProfitNames, ['Service Profit', 'Yext Profit']);
        sort($totalProfitNames);
        sort($totalExpenseNames);

        usort($profits, function($a, $b){
            return strcasecmp($a['name'], $b['name']);
        });
        usort($expenses, function($a, $b){
            return strcasecmp($a['name'], $b['name']);
        });

        //Check if this month saved
        $thisMonthExist = ProfitLossHistory::where('desired_date', Carbon::now()->startOfMonth())->first();

        $data = [
            'currentSection'        => 'profit-loss',
            'expenses'              => $expenses,
            'profits'               => $profits,
            'totalProfit'           => $totalProfit,
            'blogCount'             => $blogCount,
            'profitLossHistories'   => ProfitLossHistory::where('desired_date', '!=', Carbon::now()->startOfMonth())->orderByDesc('desired_date')->get(),
            'currentMonth'          => $currentMonth,
            'targetHistory'         => $targetHistory,
            'histories'             => $histories,
            'totalProfitNames'      => $totalProfitNames,
            'totalExpenseNames'     => $totalExpenseNames,
            'thisMonthExist'        => is_null($thisMonthExist) ? false : true
        ];
        return view("profit-loss.index", $data);
    }

    /**
     * Track month data for profit and loss
     */
    public function trackMonthProfitLoss(Request $request)
    {
        $data = [
            'profits'       => $request->input('profits'),
            'expenses'      => $request->input('expenses'),
            'totalProfit'   => $request->input('totalProfit'),
            'blogCount'     => $request->input('blogCount')
        ];

        if( !is_null($request->input('targetHistory')) && $request->input('targetHistory') != "-1" ){
            $historyExist = ProfitLossHistory::find($request->input('targetHistory'));
        }
        else{
            $historyExist = ProfitLossHistory::where('desired_date', (Carbon::now())->startOfMonth())->first();
        }
        if( !is_null($historyExist) ) {
            $historyExist->data = $data;
        }
        else {
            $historyExist = new ProfitLossHistory([
                'data'          => $data,
                'desired_date'  => (Carbon::now())->startOfMonth()
            ]);
        }
        $historyExist->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Get All Profits and Expenses
     */
    public static function getProfitsAndExpenses()
    {
        //Get Total Profit
        $websites = Website::where('archived', '0')->get();
        $serviceProfit = [
            [
                'key'   => 'internet_marketing',
                'name'  => 'SEO',
                'price' => 0
            ],
            [
                'key'   => 'support_maintenance',
                'name'  => 'Support/Maintenance',
                'price' => 0
            ],
            [
                'key'   => 'yext',
                'name'  => 'Listings Management',
                'price' => 0
            ],
            // [
            //     'key'   => 'hosting',
            //     'name'  => 'Hosting',
            //     'price' => 0
            // ],
            [
                'key'   => 'g_suite',
                'name'  => 'Google Workspace',
                'price' => 0
            ],
            // [
            //     'key'   => 'ssl',
            //     'name'  => 'SSL',
            //     'price' => 0
            // ],
            [
                'key'   => 'cmsmax_software',
                'name'  => 'CMS Max',
                'price' => 0
            ],
            [
                'key'   => 'cmsmax_ecommerce_software',
                'name'  => 'CMS Max eCommerce',
                'price' => 0
            ],
            [
                'key'   => 'cms_max_plus',
                'name'  => 'CMS Max Plus',
                'price' => 0
            ],
            [
                'key'   => 'cms_max_ecommerce_plus',
                'name'  => 'CMS Max eCommerce Plus',
                'price' => 0
            ],
            [
                'key'   => 'googleManagementFee',
                'name'  => 'Google Management Fee',
                'price' => 0
            ],
            [
                'key'   => 'social_media_management',
                'name'  => 'Social Media Management',
                'price' => 0
            ],
            [
                'key'   => 'domain',
                'name'  => 'Domain',
                'price' => 0
            ],
            [
                'key'   => 'dont_go',
                'name'  => 'DontGo',
                'price' => 0
            ],
            [
                'key'   => 'order_snapp',
                'name'  => 'OrderSnapp',
                'price' => 0
            ],
        ];
        foreach( $websites as $website ){
            foreach( $serviceProfit as $index => $profit){
                $serviceProfit[$index]['price'] += $website->getSserviceFee($profit['key'], true);
            }
        }
        $profits = Profit::get();

        //Make pretty profits array
        $prettyProfits = [];
        $totalProfit = 0;
        foreach( $serviceProfit as $profit ) {
            $profit['service_profit'] = true;
            $prettyProfits[] = $profit;
            $totalProfit += $profit['price'];
        }
        foreach( $profits as $profit ) {
            $prettyProfits[] = [
                'key'   => $profit->key,
                'name'  => $profit->name,
                'price' => $profit->price
            ];
            $totalProfit += $profit->price;
        }

        //Get Profits and Expenses
        $expenses = Expense::get()->toArray();

        return [$prettyProfits, $expenses, $totalProfit];
    }

}
