
@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-info"></i></span>
      
                    <div class="info-box-content">
                        <span class="info-box-text">Invoice Ninja</span>
                        <span class="info-box-number invoice-ninja-states-value">
                            $ 10
                        </span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                      <span class="info-box-icon bg-info elevation-1"><i class="fab fa-cuttlefish"></i></span>
        
                      <div class="info-box-content">
                          <span class="info-box-text">Chargebee</span>
                          <span class="info-box-number chargebee-states-value">
                            $ 10
                        </span>
                      </div>
                    </div>
                  </div>
            </div>
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
                                <?php
                                    $cmsMaxPrice = $website->getProductValue(\App\AngelInvoice::CRM_KEY_CMS_MAX);
                                    $billingTypeValue = empty($website->billing_type) ? ($cmsMaxPrice > 0 ? 'cms-max' : 'n/a') : $website->billing_type;
                                ?>
                                <tr 
                                    data-website-id="{{ $website->id }}"
                                    data-cms-max-price="{{ $cmsMaxPrice }}"
                                    data-billing-type="{{ $billingTypeValue }}"
                                    data-billing-amount="{{ $website->billing_amount }}">
                                    <td>
                                        <a href="{{ route('websites.edit', $website) }}" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                                            {{ $website->website }}
                                        </a>
                                    </td>

                                    <td>
                                        <a href="#" class="billing-type-value" data-value="{{ $billingTypeValue }}"></a>
                                    </td>
                                    <td>
                                        <a href="#" class="billing-amount-value" data-value="{{ $website->billing_amount }}"></a>
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
    <script src="{{ asset('assets/js/website/billing.js?v=3') }}"></script>
@endsection
