<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientApiProduct extends Model
{
    protected $fillable = [
        'client_id',
        'key',
        'value'
    ];

    public function client()
    {
        return $this->belongsTo(\App\Client::class, 'client_id');
    }
}
