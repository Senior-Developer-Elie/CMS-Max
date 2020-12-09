
@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Websites For Billing : {{ count($websites) }}</h3>
                </div>
                <div class="card-body">
                    <table id = "website-list-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Website</th>
                                <th>Billing</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $websites as $website )
                                <tr data-website-id="{{ $website->id }}">
                                    <?php
                                        $cmsMaxPrice = $website->getProductValue(\App\AngelInvoice::CRM_KEY_CMS_MAX);
                                    ?>
                                    <td>
                                        <a href="{{ route('websites.edit', $website) }}" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                                            {{ $website->website }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="billing-type-value" data-value="{{ empty($website->billing_type) ? 'n/a' : $website->billing_type }}">
                                            
                                        </a>
                                    </td>
                                    <td>
                                        ${{ prettyFloat($cmsMaxPrice) }}
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

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">
@endsection
@section('javascript')
    <script>
        var allBillingTypes = {!! json_encode($billingTypes) !!};
    </script>

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>
    <script src="{{ asset('assets/js/website/billing.js') }}"></script>
@endsection
