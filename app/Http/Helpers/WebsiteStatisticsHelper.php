<?php
namespace App\Http\Helpers;

use App\Website;
use App\Task;

use DB;
use Carbon\Carbon;

class WebsiteStatisticsHelper {

    protected static $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', "Nov", "Dec"];
    protected static $barOptions = [
        [
            'backgroundColor'       => 'rgba(60,141,188,0.9)',
            'borderColor'           => 'rgba(60,141,188,0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(59, 139, 186)',
            'pointStrokeColor'      => 'rgba(60,141,188,1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(60,141,188,1)',
        ],
        [
            'backgroundColor'       => 'rgba(210, 214, 222, 1)',
            'borderColor'           => 'rgba(210, 214, 222, 0.9)',
            'pointRadius'           => false,
            'pointColor'            => 'rgba(210, 214, 222, 0.8)',
            'pointStrokeColor'      => 'rgb(193, 199, 209)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(220,220,220,1)',
        ],
        [
            'backgroundColor'       => 'rgba(255, 193, 6, 0.9)',
            'borderColor'           => 'rgba(255, 193, 6, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(254, 191, 218)',
            'pointStrokeColor'      => 'rgba(255, 193, 6, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(255, 193, 6, 1)',
        ],
        [
            'backgroundColor'       => 'rgba(220, 53, 69, 0.9)',
            'borderColor'           => 'rgba(220, 53, 69, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(219, 51, 67)',
            'pointStrokeColor'      => 'rgba(220, 53, 69, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(220, 53, 69, 1)',
        ],
        [
            'backgroundColor'       => 'rgba(41, 167, 69, 0.9)',
            'borderColor'           => 'rgba(41, 167, 69, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(40, 165, 67)',
            'pointStrokeColor'      => 'rgba(41, 167, 69, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(41, 167, 69, 1)',
        ],
        [
            'backgroundColor'       => 'rgba(102, 80, 188, 0.9)',
            'borderColor'           => 'rgba(102, 80, 188, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(100, 78, 186)',
            'pointStrokeColor'      => 'rgba(102, 80, 188, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(102, 80, 188, 1)',
        ],
        [
            'backgroundColor'       => 'rgba(197,179,131, 0.9)',
            'borderColor'           => 'rgba(197,179,131, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(195,177,129)',
            'pointStrokeColor'      => 'rgba(197,179,131, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(197,179,131, 1)',
        ],
        [
            'backgroundColor'       => 'rgba(198, 116, 80, 0.9)',
            'borderColor'           => 'rgba(198, 116, 80, 0.8)',
            'pointRadius'           => false,
            'pointColor'            => 'rgb(196,114,78)',
            'pointStrokeColor'      => 'rgba(198, 116, 80, 1)',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(198, 116, 80, 1)',
        ],
    ];
    /**
     * Get yearly chart data
     */
    public static function getCompletionStatistics()
    {
        $currentYear = Carbon::now()->year;
        $startYear = 2013;

        $datasets = [];
        for( $year = $currentYear; $year >= $startYear; $year-- )
        {
            $yearData = [];
            foreach( self::$months as $index => $monthLabel ){
                $yearData[] = self::getCompletedWebsiteCount($year, $index+1);
            }
            $yearDatasets = self::$barOptions[$currentYear - $year];
            $yearDatasets['data'] = $yearData;
            $yearDatasets['label'] = $year;

            $datasets[] = $yearDatasets;
        }
        $barChartData = [
            'labels'    => self::$months,
            'datasets'  => $datasets
        ];

        //Make Yearly Chart Data
        $labels = [];
        $yearlyData = [];
        $backgroundColors = [];
        foreach( $datasets as $datasetDetail ){
            $labels[] = $datasetDetail['label'];
            $yearlyData[] = array_sum($datasetDetail['data']);
            $backgroundColors[] = $datasetDetail['backgroundColor'];
        }
        $yearlyChartData = [
            'labels'    => $labels,
            'datasets'  => [
                [
                    'data'              => $yearlyData,
                    'backgroundColor'   => $backgroundColors
                ]
            ]
        ];

        return [$barChartData, $yearlyChartData];
    }

    /**
     * Get Completed Website Count For Month
     * @param int $year
     * @param int $month
     */
    public static function getCompletedWebsiteCount($year, $month)
    {
        $endOfMonth = Carbon::createFromDate($year, $month)->endOfMonth()->toDateString();
        $startOfMonth = Carbon::createFromDate($year, $month)->startOfMonth()->toDateString();

        $completedTasksCount = Task::where('stage_id', 10)
                                ->whereNull('website_id')
                                ->where('completed_at', '>=', $startOfMonth)
                                ->where('completed_at', '<=', $endOfMonth)
                                ->count();
        //DB::enableQueryLog();
        $websitesCount      = Website::where('type', '!=', 'no-website')
                                ->where('type', '!=', 'redirect-website')
                                ->where('completed_at', '>=', $startOfMonth)
                                ->where('completed_at', '<=', $endOfMonth)
                                ->count();
        //$query = DB::getQueryLog();
        //print_r($query);

        return $completedTasksCount + $websitesCount;
    }
}
