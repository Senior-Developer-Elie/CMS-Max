<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'price'
    ];
}
