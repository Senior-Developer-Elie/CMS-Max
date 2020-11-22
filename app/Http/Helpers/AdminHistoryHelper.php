<?php
namespace App\Http\Helpers;

use App\Blog;
use App\InnerBlog;
use App\Website;

class AdminHistoryHelper {
    /**
     * Allowed Types
     */
    public $types = [
        "add website",
        "edit website",
        "delete website",
        "change blog name",
        "complete blog",
        "upload blog",
        "upload image",
        "add inner page",
        "edit inner page",
        "complete inner task"
    ];


    /**
     * Get Notification Text
     */
    public static function getHistoryText($adminHistory)
    {
        $user = $adminHistory->user();
        if( is_null($user) ) return '';
        if( is_null($adminHistory) )
            return $adminHistory->message;

        $userNamePart = "<a href = '" . url("/admin-history?userId=" . $user->id) . "'>" . $user->name . "</a>";

        if( in_array($adminHistory->type, ['add website', 'edit website', 'delete website']) ){
            $website = Website::find($adminHistory->ref);
            if( is_null($website) )
                return $adminHistory->message;
            return ucwords($adminHistory->type) . ": <a href='" . url("/client-history?clientId=" . $website->client()->id) . "'>" . $website->name . "</a>";
        }
        else if( in_array($adminHistory->type, ["change blog name", "complete blog", "upload blog", "upload image"]) ){
            $blog = Blog::find($adminHistory->ref);
            if( is_null($blog) )
                return $adminHistory->message;
            $website = $blog->website();
            if( is_null($website) )
                return $adminHistory->message;
            return ucwords($adminHistory->type) . " for Client : <a href='" . url("/client-history?clientId=" . $website->client()->id) . "'>" . $website->name . "</a>";
        }
        else if( in_array($adminHistory->type, ["add inner page", "edit inner page", "complete inner task"]) ) {
            $innerBlog = InnerBlog::find($adminHistory->ref);
            if( is_null($innerBlog) )
                return $adminHistory->message;
            $website = $innerBlog->website();
            if( is_null($website) )
                return $adminHistory->message;
            return ucwords($adminHistory->type) . " for Client : <a href='" . url("/client-history?clientId=" . $website->client()->id) . "'>" . $website->name . "</a>";
        }
        return $adminHistory->message;
    }
}
