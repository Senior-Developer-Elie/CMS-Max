<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfitLossHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'data', 'desired_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data'  => 'array',
    ];
}
