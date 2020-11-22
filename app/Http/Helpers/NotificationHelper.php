<?php
namespace App\Http\Helpers;

use App\Events\NotificationStatusChangedEvent;

use App\User;
use App\Notification;
use App\Blog;
use App\InnerBlog;

class NotificationHelper {
    /**
     * Add Notification
     * @param array $attributes
     */
    public static function addNotification($attributes)
    {
        $newNotifications = Notification::create($attributes);
        event(new NotificationStatusChangedEvent());
        return $newNotifications;
    }

    /**
     * Add Notifications To Managers except for triggered by user
     * @param array $attributes
     */
    public static function addNotificationsToManagers($attributes)
    {
        $users = User::get();
        foreach( $users as $user ) {
            if( $user->can('content manager') && $user->id != $attributes['triggered_by'] ) {
                $attributes['user_id'] = $user->id;
                self::addNotification($attributes);
            }
        }
    }

    /**
     * Get notifications for user
     * @param int $user_id
     */
    public static function getNotificationsForUser($user_id)
    {
        return Notification::where('user_id', $user_id)
                    ->where('archived', 0)
                    ->orderByDesc('created_at')
                    ->get();
    }

    /**
     * Get Notification Icon
     * @param App\Notification $notification
     */
    public static function getIcon($notification)
    {
        if( $notification->type == 'assign job' )
            return 'fa fa-tasks text-success';
        if( $notification->type == 'complete job' )
            return 'fa fa-tasks text-green';
        if( $notification->type == 'complete blog' )
            return 'fab fa-blogger-b text-green';
    }

    /**
     * Get Notification Text
     * @param App\Notification $notification
     */
    public static function getText($notification, $htmlContent = false)
    {
        if( $notification->type == 'assign job' ) {
            if($htmlContent)
                return "<a href='/admin-history?userId=$notification->triggered_by'>" . $notification->triggered_by()->name . "</a>" . " assigned a task to you";
            else
                return $notification->triggered_by()->name . " assigned a task to you";
        }
        if( $notification->type == 'complete job' ) {
            if($htmlContent)
                return "<a href='/admin-history?userId=$notification->triggered_by'>" . $notification->triggered_by()->name . "</a>" . " completed a task";
            else
                return $notification->triggered_by()->name . " completed a task";

        }
        if( $notification->type == 'complete blog' ) {
            $websiteName = "";
            $blog = Blog::find($notification->reference_id);
            if( !is_null($blog) ){
                $website = $blog->website();
                if( !is_null($website) ){
                    $websiteName = $website->name;
                }
            }

            if($htmlContent)
                return "<a href='/admin-history?userId=$notification->triggered_by'>"
                    . $notification->triggered_by()->name . "</a>"
                    . " completed a blog for <strong>" . $websiteName . "</strong>";
            else
                return $notification->triggered_by()->name . " completed a blog";
        }
    }

    /**
     * Get Notification Section Type
     */
    public static function getSectionType($notification)
    {
        if( $notification->type == 'assign job' ) {
            return "Jobs To Do";
        }
        if( $notification->type == 'complete job' ) {
            return "Jobs To Do";
        }
        if( $notification->type == 'complete blog' ) {
            return "Blogs";
        }
    }

    /**
     * Get Target Text
     */
    public static function getTargetText($notification)
    {
        if( $notification->type == 'assign job' ) {
            $job = InnerBlog::find($notification->reference_id);
            if( is_null($job) )
                return "";
            return $job->title;
        }
        if( $notification->type == 'complete job' ) {
            $job = InnerBlog::find($notification->reference_id);
            if( is_null($job) )
                return "";
            return $job->title;
        }
        if( $notification->type == 'complete blog' ) {
            $blog = Blog::find($notification->reference_id);
            if( is_null($blog) )
                return "";
            return $blog->name;
        }
    }

    /**
     * Get Target Text Link
     */
    public static function getTargetTextLink($notification)
    {
        if( $notification->type == 'assign job' ) {
            $job = InnerBlog::find($notification->reference_id);
            if( is_null($job) )
                return url("/jobs");
            return url("/jobs?editInnerBlogId=" . $job->id . ( $job->marked ? '&?filter=completed' : '' ));
        }
        if( $notification->type == 'complete job' ) {
            $job = InnerBlog::find($notification->reference_id);
            return url("/jobs?filter=completed&assignee=-1&editInnerBlogId=" . (is_null($job) ? "-1" : $job->id));
        }
        if( $notification->type == 'complete blog' ) {
            $blog = Blog::find($notification->reference_id);
            if( is_null($blog) )
                return "#";
            return $blog->blog_website;
        }
    }

    /**
     * Get Complete Url List
     */
    public static function getCompleteUrlList($notification)
    {
        if( $notification->type == 'complete job' ) {
            $job = InnerBlog::find($notification->reference_id);
            if( is_null($job) )
                return [];
            return $job->website;
        }
        return [];
    }
}
