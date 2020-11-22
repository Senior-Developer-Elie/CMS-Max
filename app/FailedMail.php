<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedMail extends Model
{
    protected $fillable = [
        "id",
        "key"
    ];
}
