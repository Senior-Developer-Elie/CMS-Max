@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Mailgun Sender List</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#active-websites" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Active Websites : {{ count($activeWebsites) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#archived-websites" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Archived Websites : {{ count($archivedWebsites) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="active-websites">
                            <table id = "manage-sender-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th>Sender Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $activeWebsites as $website )
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
                                                <a href="#" class="sender-name-value" data-value="{{ $website->mailgun_sender }}"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="archived-websites">
                            <table id = "manage-sender-arrchived-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Website Url</th>
                                        <th>Sender Name</th>
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
                                                <a href="#" class="sender-name-value" data-value="{{ $website->mailgun_sender }}"></a>
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
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">
@endsection
@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/manage-sender.js?v=4') }}"></script>
@endsection
