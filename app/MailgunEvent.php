<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailgunEvent extends Model
{
    protected $fillable = [
        'event_id',
        'event',
        'severity',
        'storage_url',
        'storage_key',
        'recipient_domain',
        'reason',
        'log_level',
        'envelope_sender',
        'envelope_transport',
        'envelope_targets',
        'recipient',
        'message_to',
        'message_id',
        'message_from',
        'message_subject',
        'delivery_status_code',
        'delivery_status_message',
        'delivery_status_description',
        'timestamp',
        'signature',
        'signature_token',
        'signature_timestamp',
        'domain',
        'supression',
        'supression_error'
    ];

    public function from()
    {
        return trim(explode(' <', $this->message_from)[0]);
    }

    public function detailViewUrl()
    {
        return "https://app.mailgun.com/app/sending/domains/" . $this->domain . "/logs/" . urlencode($this->message_id) . "?url=" . urlencode($this->storage_url);
    }

    public function linkedWebsite()
    {
        $fromName = trim(explode(' <', $this->message_from)[0]);
        $websiteMatch = Website::where('mailgun_sender', $fromName)->first();
        return $websiteMatch;
    }

    public function timestampAsEST()
    {
        $timestamp = new \Carbon\Carbon($this->timestamp);
        return $timestamp->subHours(4)->format('m/d/Y h:i A');
    }

    public static function getFailedEvents($domain = false)
    {
        if( $domain == false || $domain == 'all' )
            return MailgunEvent::where('archived', 0)->where('event', 'failed');
        else
            return MailgunEvent::where('domain', $domain)->where('archived', 0)->where('event', 'failed');
    }
}
