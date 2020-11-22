<?php
namespace App\Http\Helpers;

use App\InnerBlog;
use Auth;
use Carbon\Carbon;
class JobHelper {

    /**
     * @param int $daysOffset
     */
    public static function getUpcomingJobsForUser($daysOffset = 7)
    {
        $query = InnerBlog::where('id', '>', 0);

        if( !Auth::user()->hasRole('super admin') )
            $query = $query->where('assignee_id', Auth::user()->id);

        $query = $query->where('to_do', 1)->where('marked', 0);

        $today = Carbon::today();
        $jobsForUser = $query->where('due_date', '<', $today->addDays(7))->orderBy('due_date')->get();


        return $jobsForUser;
    }
}
