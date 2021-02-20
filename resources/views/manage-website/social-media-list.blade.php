@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Social Media List <span id="websites-count"></span></h3>
                </div>
                <div class="card-body">
                    <table id = "website-list-table" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Website Name</th>
                                <th>Website Url</th>
                                <th>Plan</th>
                                <th>Budget</th>
                                <th width="40%">Notes</th>
                                {{-- <th width="120px">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $activeWebsites as $website )
                                <tr data-website-id="{{ $website->id }}">
                                    <td class="website-url-wrapper">
                                        <a href="{{ url('/client-history?clientId=' . $website->client_id) }}" data-toggle="tooltip" data-placement="top" title="Go to client" data-html="true">
                                            {{ $website->name }}
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
                                        <a href="#" class="social-budget-value" data-value="{{ $website->social_budget }}">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="social-media-notes" data-value="{{ $website->social_media_notes }}">
                                        </a>
                                    </td>
                                    {{-- <td class="text-center">
                                        <button type="button" class="btn btn-warning pull-left archive-btn">Archive Website</button>
                                    </td> --}}
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

    <script src="{{ asset('assets/js/website/social-media.js?v=12') }}"></script>
@endsection
