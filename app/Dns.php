<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dns extends Model
{
    protected $fillable = [
        "id",
        "key",
        "name",
        "description"
    ];
}
