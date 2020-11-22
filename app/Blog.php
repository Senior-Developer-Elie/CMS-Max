<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Blog extends Model
{
    protected $fillable = [
        'website_id',
        'assignee_id',
        'name',
        'desired_date',
        'marked',
        'blog_url',
        'completed_by',
        'completed_at',
        'blog_website',
        'blog_image'
    ];

    /**
     * Get the client for the blog.
     */
    public function website()
    {
        return $this->belongsTo('App\Website')->get()->first();
    }

    /**
     * Get Completed By User
     */
    public function completed_by(){
        return User::find($this->completed_by);
    }
}
