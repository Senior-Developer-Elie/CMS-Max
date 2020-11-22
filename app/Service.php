<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'name',
        'label',
        'price',
        'content'
    ];

    public static function getServices($type = 'all'){
        if( $type == 'all')
            return array_merge(self::where('type', 'one-time')->get()->toArray(), self::where('type', 'recurring')->get()->toArray());
        else if( $type == 'one-time' )
            return self::where('type', 'one-time')->get()->toArray();
        else if( $type == 'recurring' )
            return self::where('type', 'recurring')->get()->toArray();
    }

    public static function getBottomDescription(){
        return self::where('type', 'bottom-description')->first()->content;
    }
}
