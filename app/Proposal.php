<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    //Casts
    protected $casts = [
        'request' => 'array'
    ];

    protected $fillable = [
        'request',
        'status',
        'signature',
        'full_name',
        'job_title',
        'signed_at',
        'sold'
    ];
}
