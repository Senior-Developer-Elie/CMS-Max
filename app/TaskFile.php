<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class TaskFile extends Model
{
    protected $fillable = [
        'task_id',
        'origin_name',
        'path'
    ];

    // event handler
    public static function boot() {
        parent::boot();

        static::deleting(function($taskFile) {

            //Remove file from storage
            if( !is_null($taskFile->path) && $taskFile->path !== '' && Storage::disk('s3')->exists( $taskFile->path )) {
                Storage::delete($taskFile->path);
            }
        });
    }

    public function task()
    {
        return $this->belongsTo('App\Task', 'task_id')->get()->first();
    }
}
