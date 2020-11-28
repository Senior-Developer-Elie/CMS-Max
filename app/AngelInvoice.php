<?php

namespace App;

class AngelInvoice
{
    const CRM_KEY_CMS_MAX = "cms_max";
    const CRM_KEY_WEBSITE_DEVELOPMENT = "website_development";
    const CRM_KEY_SUPPORT_MAINTENANCE = "support_maintenance";
    const CRM_KEY_SOCIAL_MEDIA_SETUP = "social_media_setup";
    const CRM_KEY_SEO = "seo";
    const CRM_KEY_PINTEREST_MANAGEMENT = "pinterest_management";
    const CRM_KEY_PINTEREST_SPEND = "pinterest_spend";
    const CRM_KEY_LISTINGS_MANAGEMENT = "listings_management";
    const CRM_KEY_LINKEDIN_ADS_MANAGEMENT = "linkedin_ads_management";
    const CRM_KEY_LINKEDIN_ADS_SPEND = "linkedIn_ads_spend";
    const CRM_KEY_GRAPHIC_DESIGN = "graphic_design";
    const CRM_KEY_GOOGLE_ADS_MANAGEMENT = "google_ads_management";
    const CRM_KEY_GOOGLE_ADS_SPEND = "google_ads_spend";
    const CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM = "programmatic_display_video_platform";
    const CRM_KEY_GEO_FENCING = "geo_fencing";
    const CRM_KEY_FACEBOOK_CUSTOM = "facebook_custom";
    const CRM_KEY_FACEBOOK_ACCELERATE = "facebook_accelerate";
    const CRM_KEY_FACEBOOK_GROW = "facebook_grow";
    const CRM_KEY_FACEBOOK_BUILD = "facebook_build";
    const CRM_KEY_DONT_GO = "dont_go";
    const CRM_KEY_DOMAIN = "domain";
    const CRM_KEY_DATA_ENTRY = "data_entry";
    const CRM_KEY_COMMISSION = "commission";
    const CRM_KEY_GOOGLE_WORKSPACE = "google_workspace";

    static protected $products = [
        self::CRM_KEY_CMS_MAX => "CMS Max",
        self::CRM_KEY_WEBSITE_DEVELOPMENT => "Website Development",
        self::CRM_KEY_SUPPORT_MAINTENANCE => "Support/Maintenance",
        self::CRM_KEY_SOCIAL_MEDIA_SETUP => "Social Media Setup",
        self::CRM_KEY_SEO => "SEO",
        self::CRM_KEY_PINTEREST_MANAGEMENT => "Pinterest - Management",
        self::CRM_KEY_PINTEREST_SPEND => "Pinterest - Spend",
        self::CRM_KEY_LISTINGS_MANAGEMENT => "Listings Management",
        self::CRM_KEY_LINKEDIN_ADS_MANAGEMENT => "LinkedIn Ads - Management",
        self::CRM_KEY_LINKEDIN_ADS_SPEND => "LinkedIn Ads - Spend",
        self::CRM_KEY_GRAPHIC_DESIGN => "Graphic Design",
        self::CRM_KEY_GOOGLE_ADS_MANAGEMENT => "Google Ads - Management",
        self::CRM_KEY_GOOGLE_ADS_SPEND => "Google Ads - Spend",
        self::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM => "Programmatic Display/Video Platform",
        self::CRM_KEY_GEO_FENCING => "Geo-fencing",
        self::CRM_KEY_FACEBOOK_CUSTOM => "Facebook - Custom",
        self::CRM_KEY_FACEBOOK_ACCELERATE => "Facebook - Accelerate",
        self::CRM_KEY_FACEBOOK_GROW => "Facebook - Grow",
        self::CRM_KEY_FACEBOOK_BUILD => "Facebook - Build",
        self::CRM_KEY_DONT_GO => "DontGo",
        self::CRM_KEY_DOMAIN => "Domain",
        self::CRM_KEY_DATA_ENTRY => "Data Entry",
        self::CRM_KEY_COMMISSION => "Commission",
        self::CRM_KEY_GOOGLE_WORKSPACE => "Google Workspace",
    ];

    public static function products()
    {
        return self::$products;
    }

    public static function crmProductKeys()
    {
        return array_keys(self::$products);
    }

    public static function apiProductKeys()
    {
        return array_values(self::$products);
    }
}