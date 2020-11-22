<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'name',
        'priority'
    ];

    public function tasks()
    {
        return $this->hasMany('App\Task', 'stage_id');
    }

    public static function boot() {
        parent::boot();
        
        //When Create Assign Stage Id
        static::creating(function ($stage) {

            //Set Maximum Priority
            $maxPriorityStage = self::whereRaw('priority = (select max(`priority`) from stages)')->get()->first();
            $stage->priority = $maxPriorityStage ? ($maxPriorityStage->priority + 1) : 1;
        });
    }
}
