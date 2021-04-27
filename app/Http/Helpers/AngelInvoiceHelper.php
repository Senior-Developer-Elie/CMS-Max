<?php
namespace App\Http\Helpers;

use App\AngelInvoice;

class AngelInvoiceHelper {

    const API_FREQUENCIES = [
        4 => 1,
        9 => 12
    ];
    public static  $apiUrl = "https://app.freedominvoicing.com/api/v1";
    protected static $token = "5hfjqhupy4mckigy2k2jssysov53mgrb";

    public static function getClients($indexedById = false)
    {
        $allWithOldClients = self::get("/clients?per_page=1000");

        $allClients = [];

        foreach( $allWithOldClients as $client ) {
            if( !$client['is_deleted'] )
                $allClients[] = $client;
        }

        if( !$indexedById )
            return $allClients;

        $prettyClients = [];
        //make website as index
        foreach( $allClients as $client ) {
                $prettyClients[$client['id']] = $client;
        }
        return $prettyClients;
    }

    /**
     * Get Client
     * @param int $clientId
     */
    public static function getClient($clientId)
    {
        $client = self::get("/clients/" . $clientId);

        return $client;
    }

    /**
     * Get Invoices
     */
    public static function getInvoices()
    {
        return self::get("/invoices?per_page=100000&is_recurring=1");
    }

    public static function get($url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::$apiUrl . $url, [
            'headers' => [
                'X-Ninja-Token' => self::$token,
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $contents = json_decode($response->getBody()->getContents(), true);
        return $contents['data'];
    }

    public static function getCleanUrl($url)
    {
        $input = trim($url, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }

        $urlParts = parse_url($input);

        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);

        return $domain;
    }

    /**
     * Get Recurring invoice for client
     */
    public static function getRecurringInvoicesForClient($client_id)
    {
        $invoices = self::get("/invoices?client_id=" . $client_id);
        
        $recurringInvoices = [];
        foreach( $invoices as $invoice ){
            if( !$invoice['is_deleted'] && $invoice['is_recurring'] )
                $recurringInvoices[] = $invoice;
        }
        return $recurringInvoices;
    }

    /**
     * Get Yext and Service Price
     * @param int $client_id
     */
    public static function getPrices($client_id, $invoicesData = false)
    {
        if($invoicesData !== false)
            $invoices = $invoicesData;
        else
            $invoices = self::getRecurringInvoicesForClient($client_id);

        $prices = [];
        foreach (AngelInvoice::crmProductKeys() as $productCrmKey) {
            $prices[$productCrmKey] = [
                'price' => 0,
                'invoiceFound' => false
            ];
        }
        
        $productKeyFields = [];
        foreach (AngelInvoice::products() as $crmKey => $apiKey) {
            $productKeyFields[$crmKey] = [
                $apiKey
            ];
        }
        
        foreach($invoices as $invoice) {
            if( is_array($invoice) &&  is_array($invoice['invoice_items']) ) {
                foreach( $invoice['invoice_items'] as $item ){
                    foreach( $prices as $key => $value ) {
                        if( in_array($item['product_key'], $productKeyFields[$key]) ) {
                            $prices[$key]['price'] += $item['cost'] * $item['qty'] / (self::API_FREQUENCIES[$invoice['frequency_id']] ?? 1);
                            $prices[$key]['invoiceFound'] = true;
                        }

                    }
                }
            }
        }
        return $prices;
    }
}
