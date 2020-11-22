<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\WebsiteHelper;

class CreditCardProcessing extends Model
{
    protected $fillable = [
        'company_name',
        'payment_gateway',
        'mid',
        'control_scan_user',
        'control_scan_pass',
        'control_scan_renewal_date',
    ];

    protected $casts = [
        'payment_gateway'   => 'array'
    ];

    public function paymentGatewayString()
    {
        if( is_null($this->payment_gateway) )
            return "";
        $string = "";
        $allPaymentGateways = WebsiteHelper::getAllPaymentGateways();
        foreach( $this->payment_gateway as $gateway ) {
            $string .= $allPaymentGateways[$gateway] . ", ";
        }
        if( strlen($string) >= 2 )
            $string = substr($string, 0, -2);

        return $string;
    }
}
