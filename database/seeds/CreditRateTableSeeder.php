<?php

use Illuminate\Database\Seeder;

class CreditRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('credit_rates')->truncate();
        DB::table('credit_rates')->insert([
            [
                'content'   => json_encode([
                    [
                        'id'                => 'existing-table',
                        'label'             => 'Existing Rates & Fees',
                        'interchanges'      => [
                            [
                                'label'     => 'VS Interchange',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 4208,
                                        'rate'      => 0.81,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'MC Interchange',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 740,
                                        'rate'      => 0.81,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'DS Interchange',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 0,
                                        'rate'      => 0.81,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'AX Interchange',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 600,
                                        'rate'      => 0.81,
                                    ]
                                ]
                            ],
                        ],
                        'credits'            => [
                            [
                                'label'             => 'Visa',
                                'auth_fee'          => 0.15,
                                'sharp_transaction' => 13,
                            ],
                            [
                                'label'             => 'Mastercard',
                                'auth_fee'          => 0.15,
                                'sharp_transaction' => 3,
                            ],
                            [
                                'label'             => 'Discover',
                                'auth_fee'          => 0.15,
                                'sharp_transaction' => 0,
                            ],
                            [
                                'label'             => 'Amex',
                                'auth_fee'          => 0.15,
                                'sharp_transaction' => 2,
                            ]
                        ],
                        'pin_debit_avs' => [
                            [
                                'label'             => 'PIN Debit Fee',
                                'transaction_fee'   => 0,
                                'sharp_transaction' => 0,
                            ],
                            [
                                'label'             => 'Gateway Fee',
                                'transaction_fee'   => 0.05,
                                'sharp_transaction' => 75,
                            ],
                            [
                                'label'             => 'Additional Auths',
                                'transaction_fee'   => 0,
                                'sharp_transaction' => 0,
                            ],
                            [
                                'label'             => 'AVS',
                                'transaction_fee'   => 0,
                                'sharp_transaction' => 0,
                            ]
                        ],
                        'fees' => [
                            [
                                'label'     => 'Gateway Monthly Fee',
                                'value'    => [15]
                            ],
                            [
                                'label'              => 'PCI Non-Compliance Fee',
                                'value'                     => [125, 0, 0]
                            ],
                            [
                                'label'                     => 'Monthly vs Daily Discount Cost',
                                'value'                     => [2.77]
                            ],
                            [
                                'label'   => 'Interchange Passthrough',
                                'value'                     => [173.13]
                            ],
                            [
                                'label'                     => ' Batch/Settlement Fees',
                                'value'                     => [0]
                            ],
                        ]
                    ],
                    [
                        'id'                => 'proposed-table',
                        'label'             => 'Proposed Rates & Fees',
                        'interchanges'      => [
                            [
                                'label'     => 'VS Interchange',
                                'card_name' => 'Visa',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 4208,
                                        'rate'      => 0.5,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'MC Interchange',
                                'card_name' => 'MasterCard',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 740,
                                        'rate'      => 0.5,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'DS Interchange',
                                'card_name' => 'Discover',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 0,
                                        'rate'      => 0.5,
                                    ]
                                ]
                            ],
                            [
                                'label'     => 'AX Interchange',
                                'card_name' => 'Amex',
                                'records'   => [
                                    [
                                        'label'     => 'Discount Rate',
                                        'volume'    => 600,
                                        'rate'      => 0.5,
                                    ]
                                ]
                            ],
                        ],
                        'credits'           => [
                            [
                                'label'             => 'Visa',
                                'auth_fee'          => 0.10,
                                'sharp_transaction' => 13,
                            ],
                            [
                                'label'             => 'Mastercard',
                                'auth_fee'          => 0.10,
                                'sharp_transaction' => 3,
                            ],
                            [
                                'label'             => 'Discover',
                                'auth_fee'          => 0.10,
                                'sharp_transaction' => 0,
                            ],
                            [
                                'label'             => 'Amex',
                                'auth_fee'          => 0.10,
                                'sharp_transaction' => 2,
                            ]
                        ],
                        'pin_debit_avs'     => [
                            [
                                'label'             => 'PIN Debit Fee',
                                'transaction_fee'   => 0.25,
                                'sharp_transaction' => 0,
                            ],
                            [
                                'label'             => 'Token & Encrypt',
                                'transaction_fee'   => 0.05,
                                'sharp_transaction' => 18,
                            ],
                            [
                                'label'             => 'AVS',
                                'transaction_fee'   => 0,
                                'sharp_transaction' => 0,
                            ]
                        ],
                        'fees'              => [
                            [
                                'label'     => 'Monthly Service Fee',
                                'value'    => [20, 0, 0, 0, 0]
                            ],
                            [
                                'label'   => 'Interchange Passthrough',
                                'value'                     => [173.13]
                            ],
                            [
                                'label'                     => '(31 at $.10) Batch/Settlement Fees',
                                'value'                     => [3.10]
                            ],
                        ],
                        'buy_rate'          => 0.01,
                        'trans_buy_rate'    => 0.05,
                        'make_percent'      => 0.85,
                        'monthly_fee_rate'  => 5.88
                    ]
                ])
            ]
        ]
        );
    }
}
