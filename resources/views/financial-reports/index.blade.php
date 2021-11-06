@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Manage Sales & Expenses</h2>

                    <div class="card-tools pull-right">
                        <a href="{{ route('financial-reports.create') }}">
                            <button type="button" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add Monthly Report
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="financial-reports-table" class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 100px">Date</th>
                                <th>Sales</th>
                                <th>Expense</th>
                                <th>Profit</th>
                                @foreach ($profitNames as $profitName)
                                    <th style="white-space: nowrap;">{{ $profitName }}</th>
                                @endforeach
                                @foreach ($expenseNames as $expenseName)
                                    <th style="white-space: nowrap;">{{ $expenseName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($financialReports as $financialReport)
                                <tr>
                                    <td style="min-width: 100px">
                                        <a href="{{ route('financial-reports.edit', $financialReport) }}">
                                            {{ \Carbon\Carbon::parse($financialReport->date)->format('M Y') }}
                                        </a>
                                    </td>
                                    <td class="text-success">
                                        ${{ prettyFloat($profit = $financialReport->profitItems()->sum('value')) }}
                                    </td>
                                    <td class="text-danger">
                                        ${{ prettyFloat($expense = $financialReport->expenseItems()->sum('value')) }}
                                    </td>
                                    <td class="text-primary">
                                        ${{ prettyFloat($profit - $expense) }}
                                    </td>
                                    @foreach ($profitNames as $profitName)
                                        <td class="text-success">
                                            {{ isset($financialReport->profitItemsArray[$profitName]) ? ("$" . prettyFloat($financialReport->profitItemsArray[$profitName])) : 'N/A'}}
                                        </td>
                                    @endforeach
                                    @foreach ($expenseNames as $expenseName)
                                        <td class="text-danger">
                                            {{ isset($financialReport->expenseItemsArray[$expenseName]) ? ("$-" . prettyFloat($financialReport->expenseItemsArray[$expenseName])) : 'N/A'}}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/website/financial-reports.js?v=24') }}"></script>
@endsection