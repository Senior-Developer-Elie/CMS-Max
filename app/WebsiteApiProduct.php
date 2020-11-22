<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteApiProduct extends Model
{
    protected $fillable = [
        'website_id',
        'key',
        'value',
        'frequency',
    ];
}
