@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Websites For Google Ads : {{ count($websites) }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="greater-than-one" {{ $filterStatus == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="greater-than-one">Show websites with a value of $1 or greater</label>
                    </div>
                    <table id = "website-list-table" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Website Url</th>
                                <th class="text-center">Google Ads - Spend</th>
                                <th class="text-center">Google Ads - Management</th>
                                <th class="text-center">Programmatic Display/Video Platform</th>
                                <th width="200px">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $websites as $website )
                                <tr data-website-id="{{ $website->id }}">
                                    <td>
                                        <a href = "//{{ $website->website }}" data-value="{{ $website->website }}" target="_blank">
                                            {{ $website->website }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span data-value="{{ $website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_SPEND) }}">
                                            {{ getPrettyServiceString($website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_SPEND)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $googleManagementFee = $website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_MANAGEMENT);
                                            $googleAdsFee = $website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_ADS_SPEND);
                                            $googleMangementFeeString = getPrettyServiceString($googleManagementFee);
                                            if( $googleAdsFee > 0 && $googleManagementFee > 0 )
                                                $googleMangementFeeString .= '(' . intval($googleManagementFee * 100 / $googleAdsFee) . '%)';

                                        ?>
                                        <span data-value="{{ $googleManagementFee }}">
                                            {{ $googleMangementFeeString }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span data-value="{{ $website->getProductValue(\App\AngelInvoice::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM) }}">
                                            {{ getPrettyServiceString($website->getProductValue(\App\AngelInvoice::CRM_KEY_PROGRAMMATIC_DISPLAY_VIDEO_PLATFORM)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="marketing-notes" data-value="{{ $website->marketing_notes }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                            </tr>
                        </tfoot>
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
    <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
@endsection
@section('javascript')
    <script>
        var isGoogleAdsUser = {{ Auth::user()->job_title() == 'Google Ads' ? 'true' : 'false' }};
    </script>

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/marketing.js?v=7') }}"></script>
@endsection
