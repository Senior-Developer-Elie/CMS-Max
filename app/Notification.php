<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'triggered_by',
        'reference_id',
        'archived'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->get()->first();
    }

    public function triggered_by()
    {
        return $this->belongsTo('App\User', 'triggered_by')->get()->first();
    }
}
