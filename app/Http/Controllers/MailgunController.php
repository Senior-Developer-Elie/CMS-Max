<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MailgunHelper;
use Illuminate\Http\Request;

use App\MailgunEvent;
use App\MailgunApiKey;
use App\MailgunSuppression;
use App\Website;
use Mail;
use Mailgun\Exception\HttpClientException;
use \Carbon\Carbon;

class MailgunController extends Controller
{

    private static $mailgunEventsColumnNames = [
        0	=> 'timestamp',
	    1 	=> 'domain',
        2	=> 'severity'
    ];

    private static $mailgunSuppressionColumnNames = [
        0   => 'domain',
        1   => 'type',
        2   => 'address',
        3   => 'timestamp'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['newEvent']]);
    }

    /**
     * Mailgun api list
     */
    public function index(Request $request)
    {
        $mailgunApiKeys = MailgunApiKey::all();

        return view('manage-mailgun.index', [
            'currentSection'    => 'mailgun-api-keys',
            'mailgunApiKeys'    => $mailgunApiKeys
        ]);
    }

    /**
     * List of Failed Emails
     */
    public function failedEmails(Request $request)
    {
        $domainFilter = empty($request->input('domain')) ? 'all' : $request->input('domain');

        $totalFailedMailsCount = MailgunEvent::getFailedEvents($domainFilter)->count();
        $totalSuppressionsCount = MailgunSuppression::getSuppressions($domainFilter)->count();

        $mailgunApiKeys = MailgunApiKey::all();

        //Websites
        $allWebsites = Website::orderBy('name')->get();
        $prettyWebsites = [];
        foreach( $allWebsites as $website ){
            if( is_null($website->mailgun_sender) || $website->mailgun_sender == "" ){
                $prettyWebsites[] = [
                    'value' => $website->id,
                    'text'  => $website->name
                ];
            }
        }

        return view('manage-mailgun.failed-mails', [
            'currentSection'            => 'failed-mails',
            'mailgunApiKeys'            => $mailgunApiKeys,
            'totalFailedMailsCount'     => $totalFailedMailsCount,
            'totalSuppressionsCount'    => $totalSuppressionsCount,
            'allWebsites'               => $prettyWebsites,
            'domainFilter'              => $domainFilter
        ]);
    }

    /**
     * Get Datatable For Failed Mailguns
     */
    public function getMailgunEventsDatatable(Request $request)
    {

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $filter = $request->input('search');
        $domainFilter = $request->input('domainFilter');
        $order = $request->input('order');

        $search = (isset($filter['value']))? $filter['value'] : false;

        [$totalLengh, $failedMails] = $this->getFailedMails($domainFilter, $start, $length, $order, $search);

        $data = array(
            'draw' => $draw,
            'recordsTotal' => $totalLengh,
            'recordsFiltered' => $totalLengh,
            'data' => $failedMails,
        );

        echo json_encode($data);
    }

    /**
     * Get Datatable For Suppressions
     */
    public function getMailgunSuppressionsDatatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $filter = $request->input('search');
        $domainFilter = $request->input('domainFilter');
        $order = $request->input('order');

        $search = (isset($filter['value']))? $filter['value'] : false;

        [$totalLengh, $suppressions] = $this->getSuppressions($domainFilter, $start, $length, $order, $search);

        $data = array(
            'draw' => $draw,
            'recordsTotal' => $totalLengh,
            'recordsFiltered' => $totalLengh,
            'data' => $suppressions,
        );

        echo json_encode($data);
    }

    /**
     * Add Mailgun Api Key
     */
    public function addApiKey(Request $request)
    {
        $mailgunApiKeyId = $request->input('mailgunApiKeyId');
        $domain = $request->input('domain');
        $key = $request->input('key');

        $mailgunHelper = new MailgunHelper($key);

        if( $mailgunHelper->checkAndUpdateWebhooks($domain) == FALSE ){
            return response()->json([
                'status'    => 'error',
                'message'   => 'Domain or api key is not correct!'
            ]);
        }

        if( $mailgunApiKeyId == -1 ){//Add

            $mailgunApiKey = new MailgunApiKey([
                'domain'    => $domain,
                'key'       => $key
            ]);
            $mailgunApiKey->save();
        }
        else{//Edit
            $mailgunApiKey = MailgunApiKey::find($mailgunApiKeyId);
            if( is_null($mailgunApiKey) ){
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Somethign went wrong. Please Try Again!'
                ]);
            }
            $mailgunApiKey->domain = $domain;
            $mailgunApiKey->key = $key;
            $mailgunApiKey->save();
        }
        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Delete Mailgun Api Key
     */
    public function deleteApiKey(Request $request)
    {
        $mailgunApiKeyId = $request->input('mailgunApiKeyId');

        $mailgunApiKey = MailgunApiKey::find($mailgunApiKeyId);
        if( is_null($mailgunApiKey) )
            return response()->json([
                'status'    => 'error'
            ]);

        $mailgunHelper = new MailgunHelper($mailgunApiKey->key);
        $mailgunHelper->deleteWebhooks($mailgunApiKey->domain);

        $mailgunApiKey->delete();
        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive Failed Mail
     */
    public function archiveFailedMail(Request $request)
    {
        $mailgunEvent = MailgunEvent::find($request->input('eventId'));

        if( is_null($mailgunEvent) )
            return response()->json([
                'status'    => 'error'
            ]);

        $mailgunEvent->archived = 1;
        $mailgunEvent->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive All Failed Mails
     */
    public function archiveAllFailedMails(Request $request)
    {
        $domainFilter = $request->input('domainFilter');
        if( is_null($domainFilter) )
            return response()->json([
                'status'    => 'error'
            ]);
        MailgunEvent::getFailedEvents($domainFilter)->update(['archived'=>1]);
        return response()->json([
            'status'    => 'success'
        ]);
    }


    /**
     * Webhook New Event
     */
    public function newEvent(Request $request)
    {
        $data = $request->all();

        if( !isset($data['event-data']) || !isset($data['signature']) )
            return false;

        $eventData = $data['event-data'];
        $signature = $data['signature'];

        if( !isset($eventData['event']) || !in_array($eventData['event'], ['failed', 'complain'])  )
            return false;

        try{
            $mailgunApiKey = MailgunApiKey::where('domain', $data['domain'])->first();
            if( !is_null($mailgunApiKey) ){

                $mailgunHelper = new MailgunHelper($mailgunApiKey->key);
                if( $eventData['event'] == 'failed' && $eventData['severity'] = 'permanent' ){  //failed event

                    $bounces = $mailgunHelper->getSuppressions($mailgunApiKey->domain, 'bounce');
                    $mailgunHelper->refreshSuppressions($mailgunApiKey, 'bounce', $bounces);
                }
                else if( $eventData['event'] == 'complain' ){   //Complain

                    $complains = $mailgunHelper->getSuppressions($mailgunApiKey->domain, 'compliant');
                    $mailgunHelper->refreshSuppressions($mailgunApiKey, 'compliant', $complains);
                }
            }
        }
        catch(HttpClientException $e){

        }

        if( $eventData['event'] == 'failed' ){
            MailgunEvent::create([
                'event_id'                      => isset($eventData['id']) ? $eventData['id'] : '',
                'event'                         => $eventData['event'],
                'severity'                      => $eventData['severity'] ?? '',
                'storage_url'                   => $eventData['storage']['url'] ?? '',
                'storage_key'                   => $eventData['storage']['key'] ?? '',
                'recipient_domain'              => $eventData['recipient-domain'] ?? '',
                'reason'                        => $eventData['reason'] ?? '',
                'log_level'                     => $eventData['log-level'] ?? '',
                'envelope_sender'               => $eventData['envelope']['sender'] ?? '',
                'envelope_transport'            => $eventData['envelope']['transport'] ?? '',
                'envelope_targets'              => $eventData['envelope']['targets'] ?? '',
                'recipient'                     => $eventData['recipient'] ?? '',
                'message_to'                    => $eventData['message']['headers']['to'] ?? '',
                'message_id'                    => $eventData['message']['headers']['message-id'] ?? '',
                'message_from'                  => $eventData['message']['headers']['from'] ?? '',
                'message_subject'               => $eventData['message']['headers']['subject'] ?? '',
                'delivery_status_code'          => $eventData['delivery-status']['code'] ?? '',
                'delivery_status_message'       => $eventData['delivery-status']['message'] ?? '',
                'delivery_status_description'   => $eventData['delivery-status']['description'] ?? '',
                'timestamp'                     => (new \DateTimeImmutable())->setTimestamp((int) $eventData['timestamp'])->format('Y-m-d H:i:s'),
                'signature'                     => $signature['signature'],
                'signature_token'               => $signature['token'],
                'signature_timestamp'           => (new \DateTimeImmutable())->setTimestamp((int) $signature['timestamp'])->format('Y-m-d H:i:s'),
                'domain'                        => $data['domain']
            ]);
        }
    }

    /**
     * Delete Suppression
     */
    public function deleteSuppression(Request $request)
    {
        $suppression = MailgunSuppression::find($request->input('suppressionId'));

        if( is_null($suppression) )
            return response()->json([
                'status'    => 'error'
            ]);

        $address = $suppression->address;
        $type = $suppression->type;

        $mailgunApiKey = $suppression->mailgunApiKey();
        $suppression->delete();

        if( !is_null($mailgunApiKey) ){
            $mailgunHelper = new MailgunHelper($mailgunApiKey->key);
            $mailgunHelper->removeSuppression($mailgunApiKey->domain, $type, $address);
        }

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive Suppression
     */
    public function archiveSuppression(Request $request)
    {
        $suppression = MailgunSuppression::find($request->input('suppressionId'));

        if( is_null($suppression) )
            return response()->json([
                'status'    => 'error'
            ]);

        $suppression->archived = 1;
        $suppression->archived_at = Carbon::now();
        $suppression->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive All Suppressions
     */
    public function archiveAllSuppressions(Request $request)
    {
        $domainFilter = $request->input('domainFilter');
        if( is_null($domainFilter) )
            return response()->json([
                'status'    => 'error'
            ]);
        MailgunSuppression::getSuppressions($domainFilter)->update(['archived'=>1]);
        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Get Failed Mails
     */
    private function getFailedMails($domainFilter, $start, $length, $order, $search)
    {
        $totalLengh = MailgunEvent::getFailedEvents($domainFilter)->count();
        $query = MailgunEvent::getFailedEvents($domainFilter);

        if(  !empty($order) && is_array($order) && isset($order[0]['column']) ){

            $sortColumn = $order[0]['column'];
            $sortDirection = $order[0]['dir'];
            if( $sortDirection == 'asc' )
                $query = $query->orderBy(self::$mailgunEventsColumnNames[$sortColumn]);
            else
                $query = $query->orderByDesc(self::$mailgunEventsColumnNames[$sortColumn]);
        }
        if (!empty($start) && !empty($length)) {
            $query = $query->skip($start)->take($length);
        }
        $failedMails = $query->get();

        $prettyFailedMails = [];
        foreach( $failedMails as $mailgunEvent ){
            $prettyMail = $mailgunEvent->toArray();
            $prettyMail['detailViewUrl'] = $mailgunEvent->detailViewUrl();
            $prettyMail['timestamp_val'] = (new Carbon($mailgunEvent->timestamp))->timestamp;
            $prettyMail['timestamp_est'] = $mailgunEvent->timestampAsEST();
            $prettyMail['pretty_message'] = empty($mailgunEvent->delivery_status_message) ? $mailgunEvent->delivery_status_description : $mailgunEvent->delivery_status_message;
            $prettyMail['pretty_sender_name'] = $mailgunEvent->from();
            $linkedWebsite = $mailgunEvent->linkedWebsite();
            if( is_null($linkedWebsite) )
                $prettyMail['linkedWebsite'] = null;
            else{
                $prettyMail['linkedWebsite'] = getCleanUrl($linkedWebsite->website);
                $prettyMail['linkedWebsiteName'] = $linkedWebsite->name;
            }
            $prettyFailedMails[] = $prettyMail;
        }
        return [$totalLengh, $prettyFailedMails];
    }

    /**
     * Get Suppressions
     */
    private function getSuppressions($domainFilter, $start, $length, $order, $search)
    {
        $totalLengh = MailgunSuppression::getSuppressions($domainFilter)->count();

        $query = MailgunSuppression::getSuppressions($domainFilter);

        if(  !empty($order) && is_array($order) && isset($order[0]['column']) ){

            $sortColumn = $order[0]['column'];
            $sortDirection = $order[0]['dir'];
            if( $sortDirection == 'asc' )
                $query = $query->orderBy(self::$mailgunSuppressionColumnNames[$sortColumn]);
            else
                $query = $query->orderByDesc(self::$mailgunSuppressionColumnNames[$sortColumn]);
        }
        if (!empty($start) && !empty($length)) {
            $query = $query->skip($start)->take($length)->get();
        }
        $suppressions = $query->get();

        $prettySuppressions = [];
        foreach( $suppressions as $suppression ){
            $prettySuppression = $suppression->toArray();
            $prettySuppression['timestamp_est'] = $suppression->timestampAsEST();
            $prettySuppressions[] = $prettySuppression;
        }
        return [$totalLengh, $prettySuppressions];
    }
}
