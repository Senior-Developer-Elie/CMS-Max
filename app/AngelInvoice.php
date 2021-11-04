<?php

namespace App;

use Illuminate\Support\Arr;
class AngelInvoice
{
    const CRM_KEY_CMS_MAX = "cms_max";
    const CRM_KEY_WEBSITE_DEVELOPMENT = "website_development";
    const CRM_KEY_SUPPORT_MAINTENANCE = "support_maintenance";
    const CRM_KEY_PINTEREST_SPEND = "pinterest_spend";
    const CRM_KEY_LISTINGS_MANAGEMENT = "listings_management";
    const CRM_KEY_LINKEDIN_ADS_SPEND = "linkedIn_ads_spend";
    const CRM_KEY_GOOGLE_ADS_MANAGEMENT = "google_ads_management";
    const CRM_KEY_GOOGLE_ADS_SPEND = "google_ads_spend";
    const CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM = "programmatic_display_video_platform";
    const CRM_KEY_DONT_GO = "dont_go";
    const CRM_KEY_COMMISSION = "commission";
    const CRM_KEY_GOOGLE_WORKSPACE = "google_workspace";
    const CRM_KEY_SNAPCHAT_ADS_SPEND = "snapchat-ads-spend";
    const CRM_KEY_TIKTOK_ADS_SPEND = "tiktok-ads-spend";
    const CRM_KEY_FACEBOOK_ADS_SPEND = "facebook-ads-spend";
    const CRM_KEY_SEO_AND_SUPPORT = "seo-and-support";
    const CRM_KEY_SOCIAL_MANAGEMENT = "social-management";

    const PRODUCTS = [
        self::CRM_KEY_CMS_MAX => "CMS Max",
        self::CRM_KEY_WEBSITE_DEVELOPMENT => "Website Development",
        self::CRM_KEY_SUPPORT_MAINTENANCE => "Support/Maintenance",
        self::CRM_KEY_PINTEREST_SPEND => "Pinterest Ads - Spend",
        self::CRM_KEY_LISTINGS_MANAGEMENT => "Listings Management",
        self::CRM_KEY_LINKEDIN_ADS_SPEND => "LinkedIn Ads - Spend",
        self::CRM_KEY_GOOGLE_ADS_MANAGEMENT => "Google Ads - Management",
        self::CRM_KEY_GOOGLE_ADS_SPEND => "Google Ads - Spend",
        self::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM => "Programmatic Platform",
        self::CRM_KEY_DONT_GO => "DontGo",
        self::CRM_KEY_COMMISSION => "Commission",
        self::CRM_KEY_GOOGLE_WORKSPACE => "Google Workspace",
        self::CRM_KEY_SNAPCHAT_ADS_SPEND => "SnapChat Ads - Spend",
        self::CRM_KEY_TIKTOK_ADS_SPEND => "TikTok Ads - Spend",
        self::CRM_KEY_FACEBOOK_ADS_SPEND => "Facebook Ads - Spend",
        self::CRM_KEY_SEO_AND_SUPPORT => "SEO & Support",
        self::CRM_KEY_SOCIAL_MANAGEMENT => "Social Management",
    ];

    const EXPENSE_CRM_PRODUCT_KEYS = [
        self::CRM_KEY_CMS_MAX,
        self::CRM_KEY_PINTEREST_SPEND,
        self::CRM_KEY_LINKEDIN_ADS_SPEND,
        self::CRM_KEY_GOOGLE_ADS_SPEND,
        self::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM,
        self::CRM_KEY_DONT_GO
    ];

    const SOCIAL_PLANS_CRM_PRODUCT_KEYS = [
    ];

    const BUDGETING_PRODUCT_KEYS = [
        self::CRM_KEY_CMS_MAX,
        self::CRM_KEY_SUPPORT_MAINTENANCE,
        self::CRM_KEY_PINTEREST_SPEND,
        self::CRM_KEY_LISTINGS_MANAGEMENT,
        self::CRM_KEY_LINKEDIN_ADS_SPEND,
        self::CRM_KEY_GOOGLE_ADS_MANAGEMENT,
        self::CRM_KEY_GOOGLE_ADS_SPEND,
        self::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM,
        self::CRM_KEY_GOOGLE_WORKSPACE,
        self::CRM_KEY_SNAPCHAT_ADS_SPEND,
        self::CRM_KEY_TIKTOK_ADS_SPEND,
        self::CRM_KEY_FACEBOOK_ADS_SPEND,
        self::CRM_KEY_SEO_AND_SUPPORT,
        self::CRM_KEY_SOCIAL_MANAGEMENT,
    ];

    public static function products()
    {
        return self::PRODUCTS;
    }

    public static function crmProductKeys()
    {
        $products = self::PRODUCTS;
        asort($products);
        return array_keys($products);
    }

    public static function apiProductKeys()
    {
        $products = self::PRODUCTS;
        asort($products);

        return array_values($products);
    }

    public static function budgetingCrmProductKeys()
    {
        return self::BUDGETING_PRODUCT_KEYS;
    }

    public static function budgetingApiProductKeys()
    {
        $products = self::PRODUCTS;
        asort($products);

        foreach ($products as $key => $value) {
            if (!in_array($key, self::BUDGETING_PRODUCT_KEYS)) {
                unset($products[$key]);
            }
        }

        return array_values($products);
    }

    public static function crmProductKeysWithAdditionalValues()
    {
        return [
            self::CRM_KEY_LISTINGS_MANAGEMENT
        ];
    }

    public static function expenseCrmProductKeys()
    {
        return self::EXPENSE_CRM_PRODUCT_KEYS;
    }

    public static function socialPlanProducts()
    {
        return Arr::only(self::PRODUCTS, self::SOCIAL_PLANS_CRM_PRODUCT_KEYS);
    }
}