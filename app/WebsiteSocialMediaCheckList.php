<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSocialMediaCheckList extends Model
{
    protected $fillable = [
        'website_id',
        'social_media_check_list_id',
        'completed_at',
        'user_id',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function socialMediaCheckList()
    {
        return $this->belongsTo(socialMediaCheckList::class);
    }
}
