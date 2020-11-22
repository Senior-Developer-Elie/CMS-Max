<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class InnerBlogFile extends Model
{
    protected $fillable = [
        'inner_blog_id',
        'status',
        'origin_name',
        'file_type',
        'path',
    ];

    // event handler
    public static function boot() {
        parent::boot();

        static::deleting(function($innerBlogFile) {

            //Remove file from storage
            if( !is_null($innerBlogFile->path) && $innerBlogFile->path !== '' && Storage::disk('s3')->exists($innerBlogFile->path) ) {
                Storage::delete($innerBlogFile->path);
            }
        });
    }

    public function innerBlog()
    {
        return $this->belongsTo('App\InnerBlog', 'inner_blog_id')->get()->first();
    }
}
