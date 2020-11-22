<?php
namespace App\Http\Helpers;

use App\Website;

class DashboardNotificationHelper {
    
    /**
     * Get Dashboard Notifications
     */
    public static function get()
    {
        $notifications = [];

        $notifications = array_merge($notifications, self::getGoogleDriveMissingWebsites());

        return $notifications;
    }

    /**
     * Get websites missing google drive link
     */
    public static function getGoogleDriveMissingWebsites()
    {
        $data['websitesMissingGoogleDriveCount'] = Website::where('drive', '')->count();

        if ($data['websitesMissingGoogleDriveCount'] == 0) {
            return [];
        }

        $data['firstWebsiteId'] = Website::where('drive', '')->first()->id;

        return [ view('dashboard-notifications.websites-missing-google-drive', $data)->render() ] ;
    }
}
