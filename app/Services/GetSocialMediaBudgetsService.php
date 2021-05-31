<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Website;

class GetSocialMediaBudgetsService
{
    public static function call()
    {
        $totalAdSpend = Website::where('archived', 0)
            ->where('social_media_archived', 0)
            ->where('social_ad_spend', '>', 0)
            ->sum('social_ad_spend');
        
        $totalManagementFee = Website::where('archived', 0)
            ->where('social_media_archived', 0)
            ->where('social_management_fee', '>', 0)
            ->sum('social_management_fee');

        $spendsByAssignee = Website::where('websites.archived', 0)
            ->where('social_media_archived', 0)
            ->where('websites.social_media_assignee', '>', 0)
            ->select([
                'users.name as assignee_name',
                DB::raw('SUM(websites.social_ad_spend) as total_ad_spend'),
                DB::raw('SUM(websites.social_management_fee) as total_management_fee'),
            ])
            ->join('users', 'users.id', '=', 'websites.social_media_assignee')
            ->groupBy('websites.social_media_assignee')
            ->orderBy('assignee_name')
            ->get();

        return [
            'totalAdSpend' => $totalAdSpend,
            'totalManagementFee' => $totalManagementFee,
            'spendsByAssignee' => $spendsByAssignee,
        ];
    }
}
