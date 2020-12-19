@extends('layouts.theme')

@section('content-header')
    <h3>Add Monthly Report</h3>
@endsection

@section('content')
    @include('partials.form-errors')

    <form role="form" action="{{ route("financial-reports.store") }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-lg-9">

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Date:</label>
                            <div class="">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input 
                                        type="text"
                                        class="form-control pull-right"
                                        name="date"
                                        value="{{ \Carbon\Carbon::parse(old('date', date('Y-m-d')))->format('m/Y') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <i class="fa fa-calendar-times-o"></i>
            
                                <h3 class="card-title">Sales</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-right" width="200px;">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($profits as $profit)
                                            <tr>
                                                <input type="hidden" name="profits[{{ $profit['id'] }}][id]" value="{{ $profit['id'] }}"/>
                                                <td>
                                                    {{ $profit['name'] }}
                                                    <input type="hidden" name="profits[{{ $profit['id'] }}][name]" value="{{ $profit['name'] }}"/>
                                                </td>
                                                <td class="text-right">
                                                    ${{ number_format($profit['value'], 2, '.', ',') }}
                                                </td>
                                                <input type="hidden" name="profits[{{ $profit['id'] }}][value]" class="profit-value-input" value="{{ $profit['value'] }}"/>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <i class="fa fa-calendar-times-o"></i>
            
                                <h3 class="card-title">Expenses</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-right" width="200px;">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('expenses', $expenses) as $expense)
                                            <tr class="expense-row">
                                                <input type="hidden" name="expenses[{{ $expense['id'] }}][id]" value="{{ $expense['id'] }}"/>
                                                <td>
                                                    {{ $expense['name'] }}
                                                    <input type="hidden" name="expenses[{{ $expense['id'] }}][name]" value="{{ $expense['name'] }}"/>
                                                </td>
                                                <td class="expense-value-wrapper text-right">
                                                    <span class="expense-value">
                                                        ${{ number_format($expense['value'], 2, '.', ',') }}
                                                    </span>
                                                    <input type = 'text' name="expenses[{{ $expense['id'] }}][value]"  class="expense-value-input" value="{{ $expense['value'] }}" style="display:none;">
                                                    <input type="button" class="confirm-btn" style="display:none">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Save</h3>
                    </div>
                    <div class="card-body clearfix">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-body clearfix">
                        <div class="row">
                            <div class="col-sm-9 text-right text-success">Total Sales:</div>
                            <div id="total-profit-value" class="col-sm-3 text-right"></div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-9 text-right text-danger">Total Expense:</div>
                            <div id="total-expense-value" class="col-sm-3 text-right"></div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-9 text-right text-primary">Total:</div>
                            <div id="total-value" class="col-sm-3 text-right"></div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-sm-9 text-right text-secondary">Expense Percentage:</div>
                            <div id="expense-percentage-value" class="col-sm-3 text-right"></div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/website/add-financial-report.js?v=2') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/add-financial-report.css') }}">
@endsection