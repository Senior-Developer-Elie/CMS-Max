<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    const TEMPLATE_TYPE_EVOLUTION_MARKETING = 'evolution-marketing';
    const TEMPLATE_TYPE_EVOLUTION_MARKETING_FLORIDA = 'evolution-marketing-florida';
    const TEMPLATE_TYPE_VENICE_ONWARD = 'venice-onward';
    const TEMPLATE_TYPE_LIQUOR_CMS = 'liquor-cms';
    const TEMPLATE_TYPE_CMS_MAX = 'cms-max';

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
