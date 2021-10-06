<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Comment extends Model
{
    protected $fillable = [
        'id',
        'task_id',
        'author_id',
        'content',
        'type',
        'file_path',
        'file_origin_name'
    ];

    // event handler
    public static function boot() {
        parent::boot();

        static::deleting(function($comment) {

            if( !is_null($comment->file_path) && $comment->file_path !== '' && Storage::disk('s3')->exists($comment->file_path)) {
                Storage::delete($comment->file_path);
            }
        });
    }

    /**
     * Get the task for comment
     */
    public function task()
    {
        return $this->belongsTo('App\Task', 'task_id')->get()->first();
    }

    /**
     * Get Author for comment
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }
}
