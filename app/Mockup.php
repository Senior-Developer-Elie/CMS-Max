<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mockup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mockups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ip_address',
        'title',
        'color',
        'align',
        'email',
        'mockup_id',
        'url',
        'image_path'
    ];
}
