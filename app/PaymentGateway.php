<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        "id",
        "key",
        "name",
        "description"
    ];
}
