<?php

use Illuminate\Database\Seeder;

use App\PaymentGateway;
class PaymeentGatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentGateways = [
            [
                'key' => 'n/a',
                'name' => 'N/A',   
            ],
            [
                'key' => 'need-to-sell',
                'name' => 'Need to Sell'
            ]
        ];

        foreach ($paymentGateways as $paymentGateway) {
            PaymentGateway::create($paymentGateway);
        }
    }
}
