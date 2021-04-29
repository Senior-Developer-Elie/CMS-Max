<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMediaCheckList extends Model
{
    const CHECKLIST_TYPE_CORE = 'core';
    const CHECKLIST_TYPE_FACEBOOK = 'facebook';
    const CHECKLIST_TYPE_INSTAGRAM = 'instagram';
    const CHECKLIST_TYPE_YOUTUBE = 'youtube';
    const CHECKLIST_TYPE_PINTEREST = 'pinterest';
    const CHECKLIST_TYPE_TWITTER = 'twitter';

    protected $fillable = [
        'target',
        'text',
        'order',
    ];

    protected static $checkListTypes = [
        self::CHECKLIST_TYPE_CORE => "Onboarding Core",
        self::CHECKLIST_TYPE_FACEBOOK => "Facebook",
        self::CHECKLIST_TYPE_INSTAGRAM => "Instagram",
        self::CHECKLIST_TYPE_YOUTUBE => "YouTube",
        self::CHECKLIST_TYPE_PINTEREST => "Pinterest",
        self::CHECKLIST_TYPE_TWITTER => "Twitter",
    ];

    public static function checkListTypes()
    {
        return self::$checkListTypes;
    }

    public function scopeByTarget($query, string $target)
    {
        $query->where('target', $target);

        return $query;
    }
}
