<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMediaStage extends Model
{
    protected $fillable = [
        'name',
        'order'
    ];

    public function websites()
    {
        return $this
            ->hasMany(Website::class)
            ->where('archived', 0)
            ->where('social_media_archived', 0)
            ->orderBy('name');
    }
}
