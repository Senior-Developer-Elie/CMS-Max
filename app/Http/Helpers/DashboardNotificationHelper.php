<?php
namespace App\Http\Helpers;

use App\Client;
use App\Website;

class DashboardNotificationHelper {
    
    /**
     * Get Dashboard Notifications
     */
    public static function get()
    {
        $notifications = [];

        $notifications = array_merge($notifications, self::getGoogleDriveMissingWebsites());
        $notifications = array_merge($notifications, self::getClientLeadMissingClients());
        $notifications = array_merge($notifications, self::getProjectManagerMissingClients());

        return $notifications;
    }

    /**
     * Get websites missing google drive link
     */
    public static function getGoogleDriveMissingWebsites()
    {
        $data['websitesMissingGoogleDriveCount'] = Website::where('drive', '')
            ->where('archived', 0)
            ->count();

        if ($data['websitesMissingGoogleDriveCount'] == 0) {
            return [];
        }

        $data['firstWebsiteId'] = Website::where('drive', '')->first()->id;

        return [ view('dashboard-notifications.websites-missing-google-drive', $data)->render() ] ;
    }

    /**
     * Get websites missing google drive link
     */
    public static function getClientLeadMissingClients()
    {
        $data['clientsMissingClientLead'] = Client::where('archived', 0)->whereNull('client_lead')->count();

        if ($data['clientsMissingClientLead'] == 0) {
            return [];
        }

        $data['firstClientId'] = Client::where('archived', 0)->whereNull('client_lead')->first()->id;

        return [ view('dashboard-notifications.clients-missing-client-lead', $data)->render() ] ;
    }

    /**
     * Get websites missing google drive link
     */
    public static function getProjectManagerMissingClients()
    {
        $data['clientsMissingProjectManager'] = Client::where('archived', 0)->whereNull('project_manager')->count();

        if ($data['clientsMissingProjectManager'] == 0) {
            return [];
        }

        $data['firstClientId'] = Client::where('archived', 0)->whereNull('project_manager')->first()->id;

        return [ view('dashboard-notifications.clients-missing-project-manager', $data)->render() ] ;
    }
}
