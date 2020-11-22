<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InnerBlog extends Model
{
    //Casts
    protected $casts = [
        'website' => 'array'
    ];

    protected $fillable = [
        'title',
        'website_id',
        'assignee_id',
        'priority',
        'website',
        'needed_text',
        'marked',
        'blog_url',
        'blog_image',
        'completed_by',
        'completed_at',
        'files',
        'to_do',
        'due_date'
    ];

    /**
     * Get the client for the blog.
     */
    public function website()
    {
        return $this->belongsTo('App\Website')->get()->first();
    }

    /**
     * Get the assignee for the blog.
     */
    public function assignee()
    {
        return $this->belongsTo('App\User', 'assignee_id')->get()->first();
    }

    /**
     * Get Completed By User
     */
    public function completed_by(){
        return User::find($this->completed_by);
    }

    /**
     * Override Create Method
     */
    public static function createWithPriority(array $attributes = [])
    {
        $maxPriority = self::max('priority');
        if( is_null($maxPriority) )
            $attributes['priority'] = 1;
        else
            $attributes['priority'] = $maxPriority + 1;
        return self::create($attributes);
    }

    // event handler
    public static function boot() {
        parent::boot();

        static::deleting(function($innerBlog) {

            //Delete Inner Blog Files
            $innerBlog->files()->delete();
        });
    }

    /**
     * Blog Files
     */
    public function files()
    {
        return $this->hasMany('App\InnerBlogFile', 'inner_blog_id');
    }

    /**
     * Get InnerBlog Status
     */
    public function status()
    {
        if( $this->marked )
            return 'done';
        /*
        $filesCount = count($this->files()->get());
        $finalFilesCount = count($this->files()->where('status', 'final')->get());
        if( $filesCount == 0 || $filesCount == $finalFilesCount )
            return 'pending';
        */
        return 'normal';

    }
}
