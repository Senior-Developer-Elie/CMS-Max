<?php
namespace App\Http\Helpers;
use Mailgun\Mailgun;

use App\FailedMail;
use App\MailgunApiKey;
use App\MailgunEvent;
use App\MailgunSuppression;
use App\Website;

use Illuminate\Auth\Events\Failed;
use Mailgun\Exception\HttpClientException;
class MailgunHelper {

    private $apiKey = '';
    private $mgClient = false;

    private static $apiEndpoint = 'https://api.mailgun.net/v3';
    private static $webhookUrl = 'https://crm.cmsmax.com/webhooks/new-event';
    private static $pageLimit = 9999;

    public function __construct($key)
    {
        $this->apiKey =  $key;
        $this->mgClient = Mailgun::create($this->apiKey, self::$apiEndpoint);
    }

    /**
     * Get all Suppressions
     */
    public function getSuppressions($domain, $type, $filterdByEmail = true)
    {
        $suppressions = [];
        try{
            $result = false;
            if( $type == 'bounce' )
                $result = $this->mgClient->suppressions()->bounces()->index($domain, self::$pageLimit);
            else if( $type == 'compliant' )
                $result = $this->mgClient->suppressions()->complaints()->index($domain, self::$pageLimit);

            if( $result !== false )
                $suppressions = $result->getItems();
        }
        catch(\Exception $e){
            return [];
        }

        if( $filterdByEmail != true )
            return $suppressions;

        $prettySuppressions = [];
        foreach( $suppressions as $suppression ){
            $prettySuppressions[$suppression->getAddress()] = $suppression;
        }
        return $prettySuppressions;
    }

    /**
     * Check WebHooks For domain and api key
     */
    public function checkAndUpdateWebhooks($domain)
    {
        $checkingWebhooks = [
            'temporary_fail',
            'permanent_fail',
            'complained'
        ];
        foreach( $checkingWebhooks as $webhook ){

            $targetWebhook = $this->getWebHook($domain, $webhook);

            if( $targetWebhook == FALSE ){  //webhook does not exist then create
                if( $this->createWebhook($domain, $webhook) == FALSE )
                    return FALSE;
            }
            else { //Webhook exist then update
                if( $this->updateWebHook($domain, $webhook) == FALSE )
                    return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Delete All Webhooks
     * @param string $domain
     */
    public function deleteWebhooks($domain)
    {
        $checkingWebhooks = [
            'temporary_fail',
            'permanent_fail',
            'complained'
        ];
        foreach( $checkingWebhooks as $webhook ){
            $this->deleteWebhook($domain, $webhook);
        }
        return TRUE;
    }

    /**
     * Refresh Suppressions
     * @param \App\MailgunApiKey $mailgunApiKey
     * @param string $type
     * @param array $suppressions = []
     */
    public function refreshSuppressions($mailgunApiKey, $type, $suppressions = FALSE )
    {
        if( $suppressions == FALSE )
            $suppressions = $this->getSuppressions($mailgunApiKey->domain, $type);


        //Delete suppressions from database if it's deleted on api
        $mailgunApiKey->suppressions($type)->whereNotIn('address', array_keys($suppressions))->delete();

        foreach( $suppressions as $suppression ){

            $existingSuppression = $mailgunApiKey->suppressions($type)->where('address', $suppression->getAddress())->first();

            if( is_null($existingSuppression) ){
                MailgunSuppression::create([
                    'type'      => $type,
                    'domain'    => $mailgunApiKey->domain,
                    'address'   => $suppression->getAddress(),
                    'error'     => $type == 'bounce' ? $suppression->getError() : '',
                    'timestamp' => $suppression->getCreatedAt()->format('Y-m-d H:i:s')
                ]);
            }
            else{
                $existingSuppression->error = $type == 'bounce' ? $suppression->getError() : '';
                $existingSuppression->timestamp = $suppression->getCreatedAt()->format('Y-m-d H:i:s');
                $existingSuppression->save();
            }
        }
    }


    /**
     * Get Webhooks For Domain And Api Key
     */
    private function getWebHook($domain, $webhook)
    {
        try{
            $result = $this->mgClient->webhooks()->show($domain, $webhook);
        }
        catch(HttpClientException $e){
            return FALSE;
        }
        return $result;
    }

    /**
     * Create Webhook
     * @param string $domain
     * $param string $webhook
     */
    private function createWebhook($domain, $webhook)
    {
        try{
            $result = $this->mgClient->webhooks()->create($domain, $webhook, self::$webhookUrl . '?domain=' . $domain);
        }
        catch( HttpClientException $e ){
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Update Webhook
     * @param string $domain
     * $param string $webhook
     */
    private function updateWebhook($domain, $webhook)
    {
        try{
            $result = $this->mgClient->webhooks()->update($domain, $webhook, self::$webhookUrl . '?domain=' . $domain);
        }
        catch( HttpClientException $e ){
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Delete WebHook
     */
    private function deleteWebhook($domain, $webhook)
    {
        try{
            $result = $this->mgClient->webhooks()->delete($domain, $webhook);
        }
        catch( HttpClientException $e ){
            return FALSE;
        }
        return TRUE;
    }

    public static function verify($signingKey, $token, $timestamp, $signature)
    {
        // check if the timestamp is fresh
        if (\abs(\time() - $timestamp) > 15) {
            return false;
        }

        // returns true if signature is valid
        return \hash_equals(\hash_hmac('sha256', $timestamp . $token, $signingKey), $signature);
    }

    /**
     * Remove Suppression From Domain
     */
    public function removeSuppression($domain, $type, $address)
    {
        try{
            if( $type == 'bounce' )
                $this->mgClient->suppressions()->bounces()->delete($domain, $address);
            else if( $type == 'compliant' )
                $this->mgClient->suppressions()->complaints()->delete($domain, $address);
        }
        catch(HttpClientException $e){
            return FALSE;
        }
        return TRUE;
    }
}
