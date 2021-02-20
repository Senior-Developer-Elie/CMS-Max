@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Social Media List</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Websites : {{ count($activeWebsites) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#archived-websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Archived Websites : {{ count($archivedWebsites) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="websites-wrapper">
                            <table id = "website-list-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th>Plan</th>
                                        <th>Budget</th>
                                        <th width="40%">Notes</th>
                                        <th width="120px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $activeWebsites as $website )
                                        <tr data-website-id="{{ $website->id }}">
                                            <td class="website-url-wrapper">
                                                <a href="{{ url('/client-history?clientId=' . $website->client_id) }}" data-toggle="tooltip" data-placement="top" title="Go to client" data-html="true">
                                                    {{ $website->website }}
                                                </a>
                                                <div class="website-info-icons">
                                                    @if( !empty($website->drive) )
                                                        <a class="website-google-drive-link-icon" href = "{{ $website->drive }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Google Drive">
                                                            <img src="{{ asset('assets/images/google-drive-icon.png') }}" />
                                                        </a>
                                                    @endif
                                                    @if( !empty($website->social_calendar) )
                                                        <a class="website-google-drive-link-icon" href = "{{ $website->social_calendar }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Social Calendar">
                                                            <img src="{{ asset('assets/images/social-calendar-icon.png') }}" />
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <a href = "//{{ $website->website }}" data-value="{{ $website->website }}" target="_blank">
                                                    {{ $website->website }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $website->plan ?? "" }}
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $socialMediaManagementFee = \array_sum([
                                                        $website->getProductValue(\App\AngelInvoice::CRM_KEY_FACEBOOK_CUSTOM),
                                                        $website->getProductValue(\App\AngelInvoice::CRM_KEY_FACEBOOK_ACCELERATE),
                                                        $website->getProductValue(\App\AngelInvoice::CRM_KEY_FACEBOOK_GROW),
                                                        $website->getProductValue(\App\AngelInvoice::CRM_KEY_FACEBOOK_BUILD),
                                                    ]);
                                                @endphp
                                                <span data-value="{{ $socialMediaManagementFee }}">
                                                    {{ getPrettyServiceString($socialMediaManagementFee, true) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="social-media-notes" data-value="{{ $website->social_media_notes }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-warning pull-left archive-btn">Archive Website</button>
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
                        <div class="tab-pane fade show" role="tabpanel" id="archived-websites-wrapper">
                            <table id = "archived-website-list-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th>Notes</th>
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
                                                <a href = "//{{ getCleanUrl($website->website) }}" data-value="{{ $website->website }}" target="_blank">
                                                    {{ getCleanUrl($website->website) }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="social-media-notes" data-value="{{ $website->social_media_notes }}">
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-warning pull-left unarchive-btn">Re-enable Website</button>
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
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/social-media-list.css') }}">
@endsection
@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/social-media.js?v=7') }}"></script>
@endsection
