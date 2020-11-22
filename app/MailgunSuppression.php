<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailgunSuppression extends Model
{
    protected $fillable = [
        'type',
        'domain',
        'address',
        'error',
        'timestamp',
        'timezone',
        'archived',
        'archived_at'
    ];

    public function mailgunApiKey()
    {
        return \App\MailgunApiKey::where('domain', $this->domain)->first();
    }

    public function timestampAsEST()
    {
        $timestamp = new \Carbon\Carbon($this->timestamp);
        return $timestamp->subHours(4)->format('m/d/Y h:i A');
    }

    public static function getSuppressions($domain = false)
    {
        if( $domain == false || $domain == 'all' )
            return MailgunSuppression::where('archived', 0);
        else
            return MailgunSuppression::where('archived', 0)->where('domain', $domain);
    }
}
