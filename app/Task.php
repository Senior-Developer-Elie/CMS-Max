<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        "id",
        "name",
        "description",
        "stage_id",
        "assignee_id",
        "priority",
        "dev_url",
        "live_url",
        "mail_host",
        "pre_live",
        "due_date",
        "client_id",
        "website_id",
        "completed",
        "completed_at",
        "sitemap",
        "home_page_copy"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pre_live'  => 'array',
    ];

    //Event Handler
    public static function boot() {
        parent::boot();

        //When Create Assign Stage Id
        static::creating(function ($stage) {
            //Set Maximum Priority
            $maxPriorityStage = self::whereRaw('priority = (select max(`priority`) from tasks)')->get()->first();
            if( !is_null($maxPriorityStage) )
            $stage->priority = $maxPriorityStage->priority + 1;
        });

        static::deleting(function($task) {

            //Delete Inner Blog Files
            $task->files()->delete();
            $task->comments()->delete();
        });
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    /**
     * Get Stage
     */
    public function stage()
    {
        return $this->belongsTo('App\Stage', 'stage_id')->get()->first();
    }

    /**
     * Get Client
     */
    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id')->get()->first();
    }

    /**
     * Get Website
     */
    public function website()
    {
        return $this->belongsTo('App\Website', 'website_id')->get()->first();
    }

    /**
     * Get the Assinee
     */
    public function assignee()
    {
        return $this->belongsTo('App\User', 'assignee_id')->get()->first();
    }

    /**
     * Task Files
     */
    public function files()
    {
        return $this->hasMany('App\TaskFile', 'task_id');
    }

    /**
     * Get All Comments
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'task_id');
    }
}
