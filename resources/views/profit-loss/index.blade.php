@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Manage Profit & Expenses</h2>
                </div>
                <div class="card-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#current-month-page" role="tab" aria-controls="custom-content-below-home" aria-selected="true"><strong>Editing({{ $currentMonth }})</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#histories-page" role="tab" aria-controls="custom-content-below-home" aria-selected="true">History</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" role="tabpanel" id="current-month-page">
                                <div class="form-group">
                                    <select id="target-month-select" class="pull-right form-control">
                                        <option value="-1" {{ $targetHistory == "-1" ? "selected" : "" }}>Current Month</option>
                                        @foreach ( $profitLossHistories as $profitLossHistory )
                                            <option value="{{ $profitLossHistory->id }}"  {{ $targetHistory == $profitLossHistory->id ? "selected" : "" }}>{{ (new \Carbon\Carbon($profitLossHistory->desired_date))->format('M Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Income</th>
                                            <th class="text-right" width="200px;">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($profits as $profit)
                                            <tr class="profit-row" data-profit-key="{{ $profit['key'] }}" data-profit-price="{{ $profit['price'] }}" data-profit-name = "{{ $profit['name'] }}">
                                                <td>
                                                    @if( $profit['service_profit'] ?? false == true )
                                                        <strong>{{ $profit['name'] }}</strong>
                                                    @else
                                                        {{ $profit['name'] }}
                                                    @endif
                                                </td>
                                                <td class="text-right">${{ number_format($profit['price'], 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="total-row text-blue">
                                            <td>Total Profit</td>
                                            <td class="text-right">${{ number_format($totalProfit, 2, '.', ',') }}</td>
                                        </tr>

                                        <tr class="head-row odd-background">
                                            <td>Expense</td>
                                            <td class="text-right">Price</td>
                                        </tr>

                                        @foreach ( $expenses as $expense )
                                            <tr class="expense-row odd-background" {{ $expense['key'] == 'blog' ? "data-blog-row=true" : ""}}
                                                data-expense-key="{{ $expense['key'] }}" data-expense-name = "{{ $expense['name'] }}">
                                                <td>{{ $expense['name'] }}</td>
                                                <td class="expense-value-wrapper text-right">
                                                    <span class="expense-value">${{ number_format($expense['price'], 2, '.', ',') }}</span>
                                                    <input type = 'text' class="expense-value-input" value="{{ $expense['price'] }}" style="display:none;">
                                                    <input type="button" class="confirm-btn" style="display:none">
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="total-row odd-background text-warning">
                                            <td>Total Expense</td>
                                            <td class="text-right"<span id="total-expense-value"></span></td>
                                        </tr>
                                        <tr class="total-row final-profit-row text-green">
                                            <td>Final Profit</td>
                                            <td class="text-right"><span id="final-profit"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if( Auth::user()->hasRole('super admin') )
                                    <div class="text-right">
                                            @if( $targetHistory == "-1")
                                                @if( $thisMonthExist )
                                                    <label class="text-success">
                                                        Data for current month is saved, click right button to save again.
                                                    </label>
                                                @else
                                                    <label class="text-danger">
                                                        Data for current month is not saved yet!
                                                    </label>
                                                @endif
                                            @endif
                                        <button id="save-profit-loss-btn" type="button" class="btn btn-primary confirm-btn">Save For {{ $currentMonth }}</button>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade show" role="tabpanel" id="histories-page">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach ( $histories as $history )
                                                <th class="text-right" width="200px;">
                                                    {{ (new \Carbon\Carbon($history['desired_date']))->format('M Y') }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $totalProfitNames as $profitName )
                                            <tr>
                                                <td>
                                                    {{ $profitName }}
                                                </td>
                                                @foreach ($histories as $history)
                                                    <td class="text-right">
                                                        {{ find_pretty_price($history['data']['profits'], $profitName) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach

                                        <tr class="total-row text-blue">
                                            <td>Total Profit</td>
                                            @foreach ($histories as $history)
                                                <td class="text-right">
                                                    ${{ number_format($history['data']['totalProfit'], 2, '.', ',') }}
                                                </td>
                                            @endforeach
                                        </tr>

                                        @foreach ( $totalExpenseNames as $expenseName )
                                            <tr class="odd-background">
                                                <td>
                                                    {{ $expenseName }}
                                                </td>
                                                @foreach ($histories as $history)
                                                    <td class="text-right">
                                                        @if( $expenseName == 'Blog' )
                                                            ({{ find_pretty_price($history['data']['expenses'], $expenseName) }} ✕ {{ $history['data']['blogCount'] }})${{ $history['data']['totalBlogExpense'] }}
                                                        @else
                                                            {{ find_pretty_price($history['data']['expenses'], $expenseName) }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach

                                        <tr class="total-row odd-background text-warning">
                                            <td>Total Expense</td>
                                            @foreach ($histories as $history)
                                                <td class="text-right">
                                                    ${{ number_format($history['data']['totalExpense'], 2, '.', ',') }}
                                                </td>
                                            @endforeach
                                        </tr>

                                        <tr class="total-row final-profit-row text-green">
                                            <td>Final Profit</td>
                                            @foreach ($histories as $history)
                                                <td class="text-right">
                                                    ${{ number_format($history['data']['totalProfit'] - $history['data']['totalExpense'], 2, '.', ',') }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/profit-loss.css?v=3') }}">
@endsection
@section('javascript')
    <script>
        var blogCount = {{ $blogCount }};
        var totalProfit = {{ $totalProfit }};
        var targetHistory = {{  $targetHistory }};
    </script>
    <script src="{{ asset('assets/js/profit-loss.js?v=5') }}"></script>
@endsection
