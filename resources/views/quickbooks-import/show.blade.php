@extends('layouts.theme')

@section('content-header')
    <h1>
        QuickBooks Import
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Clients : {{ count($prices) }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            @foreach ( $prices as $price )
                                <tr>
                                    <td>
                                        {{ $price['client'] }}
                                    </td>
                                    <td>
                                       ${{ number_format($price['price'], 2, '.', ',') }}
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
