<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notification;
use App\Http\Helpers\NotificationHelper;

use App\Events\NotificationStatusChangedEvent;

use Auth;

class NotificationController extends Controller
{
    /**
     * Archive Notification
     */
    public function archiveNotification(Request $request)
    {
        $notification = Notification::find($request->input('notificationId'));
        if( is_null($notification) ) {
            return response()
                ->json([
                    'status'    => 'error'
                ]);
        }
        $notification->archived = true;
        $notification->save();

        event(new NotificationStatusChangedEvent());

        return response()
            ->json([
                'status'    => 'success'
            ]);
    }

    /**
     * Archive All Notifications
     */
    public function archiveAllNotifications(Request $request)
    {
        $notifications = NotificationHelper::getNotificationsForUser(Auth::user()->id);
        foreach( $notifications as $notification ){
            $notification->archived = true;
            $notification->save();
        }

        event(new NotificationStatusChangedEvent());

        return response()
            ->json([
                'status'    => 'success'
            ]);
    }
}
