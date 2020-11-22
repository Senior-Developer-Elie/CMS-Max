<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditRate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'content'
    ];

    //Casts
    protected $casts = [
        'content' => 'array'
    ];

    /**
     * Update Credit Rate
     */
    public static function updateRateDefault($content){

        $creditRate = self::orderBy('id', 'desc')->first();
        $creditRate->content = $content;
        $creditRate->save();
    }

    /**
     * Get Credit Rate
     */
    public static function getRateDefault(){

        $creditRate = self::orderBy('id', 'desc')->first()->toArray();
        return $creditRate['content'];
    }
}
