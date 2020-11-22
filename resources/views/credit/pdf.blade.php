<html>
    <body>
        <div id = "mainWrapper">
            <h4 class="project-name">{{ $projectName }}</h4>

            <?php
                $existingTotalFee = 0;
                $proposedTotalFee = 0;
            ?>
            @foreach ($cardRatings as $table)
                <?php $totalProcessingFee = 0; ?>
                <div id = "{{ $table['id'] }}" class="ratings-table-wrapper">
                    <table class="fees-table table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="page-title highlight-cell">
                                    {{ $table['label'] }}
                                </td>
                            </tr>
                        </tbody>

                        @foreach ($table['interchanges'] as $interchange)
                            <?php
                                $totalCost = 0;
                            ?>
                            <tbody class="interchanges-body">
                                <tr class="sub-title-row">
                                    <td> {{ $interchange['label'] }} </td>
                                    <td> Volume </td>
                                    <td> Rate </td>
                                    <td> Cost </td>
                                </tr>

                                @foreach ($interchange['records'] as $record)
                                    <?php
                                        $cost = $record['volume']*$record['rate']/100;
                                        $totalCost   += $cost;
                                    ?>
                                    <tr class="record-row">
                                        <td> {{ $record['label'] }} </td>
                                        <td class="variable volume"> $<span>{{ number_format($record['volume'], 2, '.', '') }}</span> </td>
                                        <td class="variable rate"> <span>{{ number_format($record['rate'], 2, '.', '') }}</span>% </td>
                                        <td class="cost">${{ number_format($cost, 2, '.', '') }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-right highlight-cell">TOTAL: </td>
                                    <td class="total-cost highlight-cell">{{ number_format($totalCost, 2, '.', '') }}</td>
                                </tr>
                            </tbody>
                            <?php
                                $totalProcessingFee += $totalCost;
                            ?>
                        @endforeach

                        <tbody class="credits-body">
                            <?php
                                $totalCost = 0;
                            ?>
                            <tr class="sub-title-row">
                                <td>Credit</td>
                                <td>Authorization Fee</td>
                                <td># of Transactions</td>
                                <td>Cost</td>
                            </tr>
                            @foreach ($table['credits'] as $credit)
                                <?php
                                    $cost       = $credit['auth_fee']*$credit['sharp_transaction'];
                                    $totalCost  += $cost;
                                ?>
                                <tr class="credit-row">
                                    <td>
                                        {{ $credit['label'] }}
                                    </td>
                                    <td class="variable auth-fee">
                                        $<span>{{ number_format($credit['auth_fee'], 2, '.', '') }}</span>
                                    </td>
                                    <td class="variable sharp-transaction">
                                        <span>{{ $credit['sharp_transaction'] }}</span>
                                    </td>
                                    <td class="cost">
                                        ${{ number_format($cost, 2, '.', '') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-right highlight-cell">TOTAL: </td>
                                <td class="total-cost highlight-cell">${{ number_format($totalCost, 2, '.', '') }}</td>
                            </tr>
                            <?php
                                $totalProcessingFee += $totalCost;
                            ?>
                        </tbody>

                        <tbody class="pin-devs-body">
                            <?php
                                $totalCost = 0;
                            ?>
                            <tr class="sub-title-row">
                                <td>PIN Debit/AVS</td>
                                <td>Transaction Fee</td>
                                <td># of Transactions</td>
                                <td>Cost</td>
                            </tr>
                            @foreach ($table['pin_debit_avs'] as $pin_debit_av)
                                <?php
                                    $cost       = $pin_debit_av['transaction_fee']*$pin_debit_av['sharp_transaction'];
                                    $totalCost  += $cost;
                                ?>
                                <tr class="credit-row">
                                    <td>
                                        {{ $pin_debit_av['label'] }}
                                    </td>
                                    <td class="variable transaction-fee">
                                        $<span>{{ number_format($pin_debit_av['transaction_fee'], 2, '.', '') }}</span>
                                    </td>
                                    <td class="variable sharp-transaction">
                                        <span>{{ $pin_debit_av['sharp_transaction'] }}</span>
                                    </td>
                                    <td class="cost">
                                        ${{ number_format($cost, 2, '.', '') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-right highlight-cell">TOTAL: </td>
                                <td class="total-cost highlight-cell">${{ number_format($totalCost, 2, '.', '') }}</td>
                            </tr>
                            <?php
                                $totalProcessingFee += $totalCost;
                            ?>
                        </tbody>
                    </table>

                    <table class = "fees-table table table-bordered mt-1" style="margin-top:20px;">
                        <tbody class="fees-body">
                            <tr class="total-processing-fee-row">
                                <td colspan="3" class="text-right text-bold">
                                    <strong>TOTAL PROCESSING FEES:</strong>
                                </td>
                                <td class="cost">
                                    ${{ number_format($totalProcessingFee, 2, '.', '') }}
                                </td>
                            </tr>

                            <?php
                                $totalFee = $totalProcessingFee;
                            ?>
                            @foreach ($table['fees'] as $fee)
                                @foreach ($fee['value'] as $value)
                                    <?php
                                        $totalFee += $value;
                                    ?>
                                    <tr class="fee-row">
                                        <td colspan="3" class="text-right">
                                            @if( $loop->index == 0 )
                                                {{ $fee['label'] }}
                                            @endif
                                        </td>
                                        <td class="variable fee" style="width:128px;">
                                            $<span>{{ number_format($value, 2, '.', '') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-right highlight-cell">TOTAL: </td>
                                <td class="total-cost highlight-cell">${{ number_format($totalFee, 2, '.', '') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        if( $table['id'] == 'existing-table' )
                            $existingTotalFee = $totalFee;
                        else
                            $proposedTotalFee = $totalFee;
                    ?>
                </div>

                <div class="page-break"></div>
            @endforeach
        </div>

        @if( isset($ccRep) && $ccRep == 'on' )
            <?php
                $totalCommision = 0;
                $monthlyFeeCommision = 0;
                $buyRate = $cardRatings[1]['buy_rate'];
                $transBuyRate = $cardRatings[1]['trans_buy_rate'];
                $makePercent = $cardRatings[1]['make_percent'];
                $monthlyFeeRate = $cardRatings[1]['monthly_fee_rate'];
            ?>
            <div class="ratings-table-wrapper">
                <table class="fees-table table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="page-title highlight-cell">
                                Enter Your Commission Rates
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td class="text-bold">
                                <strong>Your Buy Rate</strong>
                            </td>
                            <td width="100px" class="variable buy-rate" data-indexes = "1,buy_rate">
                                {{ number_format($cardRatings[1]['buy_rate'], 2, '.', '') }}%
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="buy-rate-body">
                        @foreach ($cardRatings[1]['interchanges'] as $interchange)
                            <?php
                                $commissionCost = ($interchange['records'][0]['rate']-$buyRate)*$interchange['records'][0]['volume']/100;
                                $totalCommision += $commissionCost;
                            ?>
                            <tr>
                                <td>{{ $interchange['card_name'] }}</td>
                                <td width="100px" class="cost">${{ number_format($commissionCost, 2, '.', '') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="fees-table table table-bordered mt-1">
                    <tbody>
                        <tr>
                            <td class="text-bold">
                                <strong>Your Buy Rate</strong>
                            </td>
                            <td width="100px" class="variable trans-buy-rate" data-indexes = "1,trans_buy_rate">
                                {{ number_format($cardRatings[1]['trans_buy_rate'], 2, '.', '') }}
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="trans-buy-rate-body">
                        @foreach ($cardRatings[1]['credits'] as $credit)
                            <?php
                                $commissionCost = ($credit['auth_fee']-$transBuyRate)*$credit['sharp_transaction'];
                                $totalCommision += $commissionCost;
                            ?>
                            <tr>
                                <td>{{ $credit['label'] }} per transaction</td>
                                <td width="100px" class="cost">
                                    ${{ number_format($commissionCost, 2, '.', '') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <?php
                    $proposedFirstMonthlyServiceFee = $cardRatings[1]['fees'][0]['value'][0];
                    $monthlyFeeCommision = $proposedFirstMonthlyServiceFee - $monthlyFeeRate;
                    $estimatedCommision = $totalCommision * $makePercent + $monthlyFeeCommision;
                ?>
                <table class="fees-table table table-bordered mt-1">
                    <tbody class="result-buy-rate-body">
                        <tr>
                            <td>What % do you make?</td>
                            <td width="100px" class="variable make-percent" data-indexes = "1,make_percent">
                                {{ number_format($cardRatings[1]['make_percent'], 2, '.', '') }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Monthly Fee Rate</strong>
                            </td>
                            <td width="100px" class="variable monthly-fee-rate" data-indexes = "1,monthly_fee_rate">
                                ${{ number_format($cardRatings[1]['monthly_fee_rate'], 2, '.', '') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Monthly Fee Commission</td>
                            <td class="monthly-fee-commission">
                                ${{ number_format($monthlyFeeCommision, 2, '.', '') }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="fees-table table table-bordered mt-1">
                    <tbody class="estimated-commission-body">
                        <tr>
                            <td>
                                <strong>Your estimated commission would be</strong>
                            </td>
                            <td width="100px" class="estimated-commission">
                                ${{ number_format($estimatedCommision, 2, '.', '') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <?php
            $monthlySavingCost       = $existingTotalFee - $proposedTotalFee;
            $monthlySavingPercent    = $monthlySavingCost / $existingTotalFee * 100;
        ?>
        <div class="ratings-table-wrapper mt-1">
            <table id = "total-result-table" class = "fees-table table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-bold">
                    <tr>
                        <td colspan="3" class="text-right">Estimated Monthly Savings</td>
                        <td class="estimated-monthly-saving-cost">${{ number_format($monthlySavingCost, 2, '.', '') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Estimated % of Monthly Savings</td>
                        <td class="estimated-monthly-saving-percent">{{ number_format($monthlySavingPercent, 2, '.', '') }}%</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right highlight-cell">Estimated Annual Savings</td>
                        <td class="estimated-annual-saving-cost highlight-cell">${{ number_format($monthlySavingCost*12, 2, '.', '') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right highlight-cell">Estimated 3 Year Savings</td>
                        <td class="estimated-three-annual-saving-cost highlight-cell">${{ number_format($monthlySavingCost*36, 2, '.', '') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </body>
    <style>
        body{
            margin: 20px auto;
            width: 600px;
        }
        html{
            padding: 0;
            margin: 0;
        }
        #mainWrapper{
            width: 100%;
            padding: 0 20px;
        }
        .mt-1{
            margin-top: 20px;
        }
        .project-name{
            margin-top: 5px;
            text-align: center;
        }
        .ratings-table-wrapper{

        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        table.table-bordered, table.table-bordered > tbody, table.table-bordered > tbody > tr > td{
            border:1px solid black;
        }
        table.fees-table{
            text-align: center;
        }
        table.fees-table thead{
            display: none;
        }
        table.fees-table > tbody > tr > td{
            padding: 3px 5px;
            font-size: 16px;
        }
        table.fees-table > tbody > tr > td.page-title{
            font-size: 20px;
            font-weight: 700;
        }
        table.fees-table > tbody > tr > td.highlight-cell{
            background: rgb(51, 102, 153);
            color: white;
        }
        table.fees-table > tbody > tr.sub-title-row{
            background: rgb(192, 192, 192);
        }
        table.fees-table > tbody > tr > td.variable{
            cursor: pointer;
        }
        table.fees-table > tbody > tr > td.variable:hover{
            color: rgb(220, 30, 30);
            font-weight: 600;
        }
        table.fees-table > tbody.empty-row{
            border-color: white !important;
        }
        tbody.text-bold{
            font-weight: 700;
        }
        .page-break {
            page-break-after: always;
        }
        footer .prepared-by{
            display: inline-block;
            margin-left: 20px;
        }
        footer .phone{
            display: inline-block;
            margin-left: 74px;
        }
        footer .emaila{
            display: inline-block;
            margin-left: 58px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 82px;
            right: 82px;
            height: 50px;
        }
    </style>
</html>
