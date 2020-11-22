@extends('layouts.theme')

@section('content-header')
@stop

@section('content')
    <div class="row">
        @foreach ($cardRatings as $tableIndex => $table)
            <div id = "{{ $table['id'] }}" class="ratings-table-wrapper col-md-5 custom-col-md-5">
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

                    @foreach ($table['interchanges'] as $interchangeIndex => $interchange)
                        <tbody class="interchanges-body">
                            <tr class="sub-title-row">
                                <td> {{ $interchange['label'] }} </td>
                                <td> Volume </td>
                                <td> Rate </td>
                                <td> Cost </td>
                            </tr>

                            @foreach ($interchange['records'] as $recordIndex => $record)
                                <tr class="record-row">
                                    <td> {{ $record['label'] }} </td>
                                    <td class="variable volume" data-indexes = "{{ "$tableIndex,interchanges,$interchangeIndex,records,$recordIndex,volume" }}"> $ <input value = "{{ number_format($record['volume'], 2, '.', '') }}"> </td>
                                    <td class="variable rate" data-indexes = "{{ "$tableIndex,interchanges,$interchangeIndex,records,$recordIndex,rate" }}"> <input value = "{{ number_format($record['rate'], 2, '.', '') }}"> % </td>
                                    <td class="cost"></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="2"></td>
                                <td class="text-right highlight-cell">TOTAL: </td>
                                <td class="total-cost highlight-cell"></td>
                            </tr>
                        </tbody>
                    @endforeach

                    <tbody class="credits-body">
                        <tr class="sub-title-row">
                            <td>Credit</td>
                            <td>Authorization Fee</td>
                            <td>&sharp; of Transactions</td>
                            <td>Cost</td>
                        </tr>
                        @foreach ($table['credits'] as $creditIndex => $credit)
                            <tr class="credit-row">
                                <td>
                                    {{ $credit['label'] }}
                                </td>
                                <td class="variable auth-fee" data-indexes = "{{ "$tableIndex,credits,$creditIndex,auth_fee" }}">
                                    $ <input value = "{{ number_format($credit['auth_fee'], 2, '.', '') }}">
                                </td>
                                <td class="variable sharp-transaction" data-indexes = "{{ "$tableIndex,credits,$creditIndex,sharp_transaction" }}">
                                    <input value = "{{ $credit['sharp_transaction'] }}">
                                </td>
                                <td class="cost">
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right highlight-cell">TOTAL: </td>
                            <td class="total-cost highlight-cell"></td>
                        </tr>
                    </tbody>

                    <tbody class="pin-devs-body">
                        <tr class="sub-title-row">
                            <td>PIN Debit/AVS</td>
                            <td>Transaction Fee</td>
                            <td>&sharp; of Transactions</td>
                            <td>Cost</td>
                        </tr>
                        @foreach ($table['pin_debit_avs'] as $pinDebitIndex => $pin_debit_av)
                            <tr class="credit-row">
                                <td>
                                    {{ $pin_debit_av['label'] }}
                                </td>
                                <td class="variable transaction-fee" data-indexes = "{{ "$tableIndex,pin_debit_avs,$pinDebitIndex,transaction_fee" }}">
                                    $ <input value = "{{ number_format($pin_debit_av['transaction_fee'], 2, '.', '') }}">
                                </td>
                                <td class="variable sharp-transaction" data-indexes = "{{ "$tableIndex,pin_debit_avs,$pinDebitIndex,sharp_transaction" }}">
                                    <input value = "{{ $pin_debit_av['sharp_transaction'] }}">
                                </td>
                                <td class="cost">
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right highlight-cell">TOTAL: </td>
                            <td class="total-cost highlight-cell"></td>
                        </tr>
                    </tbody>
                </table>

                <table class = "fees-table table table-bordered mt-1 {{ $tableIndex == 1 ? 'custom-margin' : '' }}">
                    <tbody class="fees-body">
                        <tr class="total-processing-fee-row">
                            <td colspan="3" class="text-right text-bold">
                                <strong>TOTAL PROCESSING FEES:</strong>
                            </td>
                            <td class="cost">
                            </td>
                        </tr>

                        @foreach ($table['fees'] as $feeIndex => $fee)
                            @foreach ($fee['value'] as $valueIndex => $value)
                                <tr class="fee-row">
                                    <td colspan="3" class="text-right">
                                        @if( $loop->index == 0 )
                                            {{ $fee['label'] }}
                                        @endif
                                    </td>
                                    <td class="variable fee" data-indexes = "{{ "$tableIndex,fees,$feeIndex,value,$valueIndex" }}" style="width:128px;">
                                        $ <input value = "{{ number_format($value, 2, '.', '') }}">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right highlight-cell">TOTAL: </td>
                            <td class="total-cost highlight-cell"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach

        <!--Commision Rate Table -->
        <div class="col-md-2 custom-col-md-2">

            <div class="form-group cc-checkbox-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cc-rep" name="cc-rep">
                    <label class="form-check-label" for="cc-rep">I am a CC Rep</label>
                </div>
            </div>
            <div id="commision-table-wrapper" style="display:none;">
                <table class="fees-table table table-bordered">
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
                            <td width="100px" class="variable buy-rate" data-indexes = "1,buy_rate"><input value = "{{ number_format($cardRatings[1]['buy_rate'], 2, '.', '') }}"> %</td>
                        </tr>
                    </tbody>
                    <tbody class="buy-rate-body">
                        @foreach ($cardRatings[1]['interchanges'] as $interchange)
                            <tr>
                                <td>{{ $interchange['card_name'] }}</td>
                                <td width="100px" class="cost"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="fees-table table table-bordered">
                    <tbody class="mt-1">
                        <tr>
                            <td class="text-bold">
                                <strong>Your Buy Rate</strong>
                            </td>
                            <td width="100px" class="variable trans-buy-rate" data-indexes = "1,trans_buy_rate"><input value = "{{ number_format($cardRatings[1]['trans_buy_rate'], 2, '.', '') }}"></td>
                        </tr>
                    </tbody>
                    <tbody class="trans-buy-rate-body">
                        @foreach ($cardRatings[1]['credits'] as $credit)
                            <tr>
                                <td>{{ $credit['label'] }} per transaction</td>
                                <td width="100px" class="cost"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="fees-table table table-bordered">
                    <tbody class="result-buy-rate-body mt-1">
                        <tr>
                            <td>What % do you make?</td>
                            <td width="100px" class="variable make-percent" data-indexes = "1,make_percent">
                                &nbsp;&nbsp;&nbsp;<input value = "{{ number_format($cardRatings[1]['make_percent'], 2, '.', '') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Monthly Fee Rate</strong>
                            </td>
                            <td width="100px" class="variable monthly-fee-rate" data-indexes = "1,monthly_fee_rate"> $ <input value = "{{ number_format($cardRatings[1]['monthly_fee_rate'], 2, '.', '') }}"></td>
                        </tr>
                        <tr>
                            <td>Monthly Fee Commission</td>
                            <td class="monthly-fee-commission"></td>
                        </tr>
                    </tbody>
                </table>

                <table class="fees-table table table-bordered">
                    <tbody class="estimated-commission-body mt-1">
                        <tr>
                            <td>
                                <strong>Your estimated commission would be</strong>
                            </td>
                            <td width="100px" class="estimated-commission"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <div class="row mt-1">
        <div class="col-md-5 custom-col-md-5">
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
                        <td class="estimated-monthly-saving-cost"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Estimated % of Monthly Savings</td>
                        <td class="estimated-monthly-saving-percent"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right highlight-cell">Estimated Annual Savings</td>
                        <td class="estimated-annual-saving-cost highlight-cell"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right highlight-cell">Estimated 3 Year Savings</td>
                        <td class="estimated-three-annual-saving-cost highlight-cell"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-5 custom-col-md-5 d-flex align-items-center justify-content-center">
            <footer class="text-center">
                <button class="btn btn-primary" data-toggle="modal" data-target="#generatePDFModal">Generate PDF</button><br>
                <label class="powered-by mt-1">Powered by: <a href="https://www.evolutionmarketing.com" rel="nofollow">Evolution Marketing</a></label>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="generatePDFModal" tabindex="-1" role="dialog" aria-labelledby="generatePDFModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePDFModalLabel">Please input your project name.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id = "ratingForm" method="POST" action="{{ url('/generate-pdf') }}" target="_blank">
                    @csrf
                    <input type = "hidden" id = "defaultRate" name = "creditRatings" value="">
                    <input type = "hidden" id = "ccRepValue" name = "ccRep" value="off">
                    <input class="form-control" name="projectName" placeholder="Project Name...">
                </form>
            </div>
            <div class="modal-footer">
                <button id = "generate-pdf-button" type="button" class="btn btn-primary">Generate</button>
            </div>
            </div>
        </div>
    </div>
@stop

@section('name')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/credit-card/main.css') }}">
@stop

@section('javascript')
    <script>
        var cardRatings = <?php echo json_encode($cardRatings); ?>
    </script>
    <script src="{{ asset('assets/js/credit-card/main.js') }}"></script>
@stop
