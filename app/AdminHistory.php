<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminHistory extends Model
{
    protected $fillable = ['user_id', 'type', 'message', 'ref'];

    /**
     * Add History
     * @param Array $historyData
     */
    public static function addHistory($historyData)
    {
        return AdminHistory::create($historyData);
    }

    /**
     * Get the user of the admin history
     */
    public function user()
    {
        return $this->belongsTo('App\User')->get()->first();
    }
}
