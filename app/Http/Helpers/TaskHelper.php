<?php
namespace App\Http\Helpers;

class TaskHelper {

    public static function getAllEmailHost()
    {
        return [
            "g-suite"       => "Google Workspace",
            "office-365"    => "Office 365",
            "other"         => "Other",
            "not-needed"    => "Not Needed"
        ];
    }

    public static function getAllPreLiveOptions()
    {
        return [
            "setup-client-billing"          => "Setup Client Billing",
            "add-client-folder-in-g-drive"  => "Add client folder in G Drive",
            "get-domain-register-info"      => "Get Domain Register Info",
            "get-email-info"                => "Get Email Info",
            "check-thank-you-pages"         => "Check thank you pages match correct forms",
            "check-forms-have-notify"       => "Check forms have notify email setup",
            "setup-business-information"    => "Setup business information page",
            //"cross-browser-testing"         => "Cross browser testing",
            "broken-link-scanner-done"      => "Broken link scanner done",
            "google-analytics-access"       => "Google Analytics Access",
            "setup-google-search-console"   => "Setup Google Search Console",
            "checked-301-redirects"         => "Checked 301 Redirects",
            "favicon"                       => "Favicon",
            "tap-clicks"                    => "TapClicks"
        ];
    }
}
