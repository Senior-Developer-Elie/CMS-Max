<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientNotesVersion extends Model
{
    protected $fillable = [
        'client_id',
        'notes'
    ];

    public function scopeLatest($query)
    {
        $query->orderByDesc('created_at');
    }
}
