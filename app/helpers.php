<?php

/**
 * Get old checked value fora checkbox input
 *
 * @param $inputName
 * @param $defaultValue
 * @return string
 */
function old_checked($inputName, $defaultValue = null)
{
    return old($inputName, $defaultValue) ? 'checked' : '';
}

/**
 * Helper for selecting dropdown option
 *
 * @param $field
 * @param $comparison
 * @param null $defaultValue
 * @return string
 */
function old_selected($field, $comparison, $defaultValue = null)
{
    return old($field, $defaultValue) == $comparison ? 'selected' : '';
}

function get_global_notifications(){
    $notifications = [];

    //Get not assigned blog clients notifications
    $unAssignedClientsCount = 0;
    $firstUnAssignedClientId = false;
    $websites = App\Website::orderBy('name')->get();
    foreach( $websites as $website ) {
        $admin = $website->assignee();
        if( $website->is_blog_client && is_null($admin) )
        {
            $unAssignedClientsCount++;

            if( $firstUnAssignedClientId == false )
                $firstUnAssignedClientId = $website->client()->id;
        }
    }
    if( $unAssignedClientsCount > 0 ) {
        $notifications[] = [
            'icon'          => 'fa fa-users text-yellow',
            'text'          => $unAssignedClientsCount . " Websites to assign Admin",
            'link'          => url('client-history?clientId=' . $firstUnAssignedClientId),
        ];
    }

    if( is_null(Auth::user()->avatar) || Auth::user()->avatar == '' ) {
        $notifications[] = [
            'icon'  => 'fa fa-optin-monster text-blue',
            'text'  => "You can upload your photo now",
            'link'  => url('/profile'),
        ];
    }

    //Get Action Notifications
    $actionNotifications = App\Http\Helpers\NotificationHelper::getNotificationsForUser(Auth::user()->id);
    $prettyActionNotifications = [];
    foreach( $actionNotifications as $notification ) {
        $prettyActionNotifications[] = [
            'notification'  => $notification,
            'icon'          => App\Http\Helpers\NotificationHelper::getIcon($notification),
            'text'          => App\Http\Helpers\NotificationHelper::getText($notification),
            'targetLink'    => App\Http\Helpers\NotificationHelper::getTargetTextLink($notification),
        ];
    }

    return [$notifications, $prettyActionNotifications];
}

function getCleanUrl($url)
{
    $input = trim($url, '/');

    // If scheme not included, prepend it
    if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
    }

    $urlParts = parse_url($input);

    if( !$urlParts ){
        return $url;
    }

    // remove www
    $domain = preg_replace('/^www\./', '', $urlParts['host']);

    return $domain;
}

function getPrettyServiceString($price)
{
    if ($price == -1) {
        return "N/A";
    }
    if ($price == -2) {
        return "Need to Sell";
    }
    if ($price == -3) {
        return "Not Interested";
    }
    return "$" . round($price);
}
function find_pretty_price($prices, $name)
{
    foreach( $prices as $price ) {
        if( $price['name'] == $name )
            return "$" . number_format(floatval($price['price']), 2, '.', ',');
    }
    return 'N/A';
}

function prettyFloat($number)
{
    return number_format(floatval($number), 2, '.', ',');
}

function getBadgeContent($target){
    $badgeContent = '';
    if( $target == 'failed-mails' ){
        $failedMailsCount = \App\MailgunEvent::where('archived', 0)->where('event', 'failed')->count();
        $suppressionsCount = \App\MailgunSuppression::where('archived', 0)->count();
        $badgeContent = '<label class="right">';
            if( $failedMailsCount > 0 )
                $badgeContent .= '<span class="right badge badge-danger text-bg">' . $failedMailsCount . '</span>';
            if( $suppressionsCount > 0 )
                $badgeContent .= '<span class="right badge bg-purple text-bg">' . $suppressionsCount . '</span>';
        $badgeContent .= '</label>';
    }
    return $badgeContent;
}
