@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Manage Profit & Expenses</h2>

                    <div class="card-tools pull-right">
                        <a href="{{ route('financial-reports.create') }}">
                            <button type="button" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add Monthly Report
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th width="200px">Date</th>
                                <th>Profit</th>
                                <th>Expense</th>
                                <th width="150px">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($financialReports as $financialReport)
                                <tr>
                                    <td>
                                        <a href="{{ route('financial-reports.edit', $financialReport) }}">
                                            {{ \Carbon\Carbon::parse($financialReport->date)->format('m/Y') }}
                                        </a>
                                    </td>
                                    <td>
                                        ${{ number_format($profit = $financialReport->profitItems()->sum('value'), 2, '.', ',') }}
                                    </td>
                                    <td>
                                        ${{ number_format($expense = $financialReport->expenseItems()->sum('value'), 2, '.', ',') }}
                                    </td>
                                    <td>
                                        ${{ number_format($profit - $expense, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection