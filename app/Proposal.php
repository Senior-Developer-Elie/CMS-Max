<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    const TEMPLATE_TYPE_EVOLUTION_MARKETING = 'evolution-marketing';
    const TEMPLATE_TYPE_EVOLUTION_MARKETING_FLORIDA = 'evolution-marketing-florida';
    const TEMPLATE_TYPE_VENICE_ONWARD = 'venice-onward';

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
        'sold',
        'template_type'
    ];
}
