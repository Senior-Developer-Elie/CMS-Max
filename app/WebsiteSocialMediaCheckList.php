<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSocialMediaCheckList extends Model
{
    const CREATIVE_REQUESTED = "creative_requested";
    const CREATIVE_RECEIVED_AND_SENT_TO_CLIENT = "creative_received_and_sent_to_client";
    const CREATIVE_APPROVED = "creative_approved";
    const CONTENT_CALENDAR_BUILT = "content_calendar_built";
    const SECOND_CHECKS_ON_CONTENT = "second_checks_on_content";
    const CONTENT_CALENDAR_SENT_FOR_APPROVAL = "content_calendar_sent_for_approval";
    const CONTENT_CALENDAR_APPROVED = "content_calendar_approved";
    const LOADED_INTO_MANAGEMENT_SOFTWARE = "loaded_into_management_software";
    const SECOND_CHECKS_ON_CONTENT_LOAD = "second_checks_on_content_load";
    const CAMPAIGN_BUILT = "campaign_built";
    const SECOND_CHECK_ON_CAMPAIGN = "second_check_on_campaign";
    const AD_REVIEW_SENT_TO_CLIENT = "ad_review_sent_to_client";
    const AD_APPROVAL_FROM_CLIENT = "ad_approval_from_client";
    const LAUNCHED_CAMPAIGN = "launched_campaign";

    protected static $socialMediaCheckLists = [
        self::CREATIVE_REQUESTED => "Creative Requested",
        self::CREATIVE_RECEIVED_AND_SENT_TO_CLIENT => "Creative Received and Sent to Client",
        self::CREATIVE_APPROVED => "Creative Approved",
        self::CONTENT_CALENDAR_BUILT => "Content Calendar Built",
        self::SECOND_CHECKS_ON_CONTENT => "Second Checks on Content",
        self::CONTENT_CALENDAR_SENT_FOR_APPROVAL => "Content Calendar sent for Approval",
        self::CONTENT_CALENDAR_APPROVED => "Content Calendar Approved",
        self::LOADED_INTO_MANAGEMENT_SOFTWARE => "Loaded into Management Software (hootsuite)",
        self::SECOND_CHECKS_ON_CONTENT_LOAD => "Second Checks on Content Load",
        self::CAMPAIGN_BUILT => "Campaign Built",
        self::SECOND_CHECK_ON_CAMPAIGN => "Second Check on Campaign",
        self::AD_REVIEW_SENT_TO_CLIENT => "Ad Review Sent to Client",
        self::AD_APPROVAL_FROM_CLIENT => "AD Approval from Client",
        self::LAUNCHED_CAMPAIGN => "Launched Campaign",
    ];

    protected $fillable = [
        'website_id',
        'key',
        'completed_at',
        'user_id',
    ];

    public static function socialMediaCheckLists()
    {
        return self::$socialMediaCheckLists;
    }
}
