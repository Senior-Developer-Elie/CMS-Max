@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Websites For Credit Card Processing</h3>
                    <button id="add-manual-entry-button" class="btn btn-primary pull-right">Add Manual Entry</button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#active-websites-wrapper" role="tab">Active : {{ count($websites) + count($creditCardProcessings) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#pending-websites-wrapper" role="tab">Pending : {{ count($pendingWebsites) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#archived-websites-wrapper" role="tab">Archived : {{ count($archivedWebsites) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" role="tabpanel" id="active-websites-wrapper">
                            <table id = "credit-card-processing-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Url</th>
                                        <th>Payment Gateways</th>
                                        <th class="text-center">MID</th>
                                        <th class="text-center">Control Scan User</th>
                                        <th class="tekxt-center">Control Scan Pass</th>
                                        <th class="text-center">Control Scan Renewal Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $websites as $website )
                                        <tr data-website-id="{{ $website->id }}">
                                            <td class="website-url-wrapper">
                                                <a href="{{ route('websites.edit', $website) }}" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                                                    {{ $website->website }}
                                                </a>
                                                <div class="website-info-icons">
                                                    <a class="website-info-icon" href = "//{{ $website->website }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Go to Website">
                                                        <img src="{{ asset('assets/images/info-icon.png') }}" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $website->paymentGatewayString() !!}
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="mid-value" data-value="{{ $website->mid }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="control-scan-user-value" data-value="{{ $website->control_scan_user }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="control-scan-pass-value" data-value="{{ $website->control_scan_pass }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="control-scan-renewal-date-value" data-value="{{ $website->control_scan_renewal_date }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($creditCardProcessings as $creditCardProcessing)
                                        <tr data-credit-card-processing-id="{{ $creditCardProcessing->id }}">
                                            <td>
                                                <a href = "//{{ $creditCardProcessing->company_name }}" data-value="{{ $creditCardProcessing->company_name }}" target="_blank">
                                                    {{ $creditCardProcessing->company_name }}
                                                </a>
                                            </td>
                                            <td>
                                                {!! $creditCardProcessing->paymentGatewayString() !!}
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="manual-mid-value" data-value="{{ $creditCardProcessing->mid }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="manual-control-scan-user-value" data-value="{{ $creditCardProcessing->control_scan_user }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="manual-control-scan-pass-value" data-value="{{ $creditCardProcessing->control_scan_pass }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="manual-control-scan-renewal-date-value" data-value="{{ $creditCardProcessing->control_scan_renewal_date }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-danger delete-manual-entry-btn">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pending-websites-wrapper">
                            <table id = "credit-card-processing-pending-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th width="40%">Notes</th>
                                        <th width="140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $pendingWebsites as $website )
                                        <tr data-website-id="{{ $website->id }}">
                                            <td>
                                                {{ $website->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ $website->website }}" data-value="{{ $website->website }}" target="_blank">
                                                    {{ $website->website }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="credit-card-notes" data-value="{{ $website->credit_card_notes }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-warning pull-left archive-btn">Archive Website</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="archived-websites-wrapper">
                            <table id = "credit-card-processing-archived-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th width="40%">Notes</th>
                                        <th width="140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $archivedWebsites as $website )
                                        <tr data-website-id="{{ $website->id }}">
                                            <td>
                                                {{ $website->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ $website->website }}" data-value="{{ $website->website }}" target="_blank">
                                                    {{ $website->website }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="credit-card-notes" data-value="{{ $website->credit_card_notes }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                @if ($website->credit_card_archived)
                                                    <button type="button" class="btn btn-warning pull-left unarchive-btn">Re-enable Website</button>
                                                @endif
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
    </div>
    @include("manage-client.modals.archive-website")
    @include("manage-website.modals.add-credit-card-processing")
@stop

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/credit-card-processing.css?v=2') }}">
@endsection

@section('javascript')

    <script src="{{ mix('js/datatable.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/credit-card-processing.js?v=7') }}"></script>
@endsection
