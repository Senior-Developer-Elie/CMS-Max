<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogIndustry extends Model
{
    protected $fillable = ['name'];

    /**
     * Get assigned blog clients
     */
    public function websites()
    {
        return $this->hasMany('App\Website', 'blog_industry_id');
    }
}
