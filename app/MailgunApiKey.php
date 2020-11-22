<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailgunApiKey extends Model
{
    protected $fillable = [
        'domain',
        'key'
    ];

    public function suppressions($type = false)
    {
        if( $type == false )
            return \App\MailgunSuppression::where('domain', $this->domain);
        else
            return \App\MailgunSuppression::where('type', $type)->where('domain', $this->domain);
    }

    public function failedEvents()
    {
        return \App\MailgunEvent::where('domain', $this->domain);
    }

    public function totalCount()
    {
        return $this->suppressions()->where('archived', 0)->count() + $this->failedEvents()->where('archived', 0)->count();
    }
}
