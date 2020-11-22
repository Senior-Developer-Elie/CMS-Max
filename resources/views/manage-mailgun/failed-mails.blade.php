@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Mailgun Logs</h3>
                </div>
                <div class="card-body">
                    <div class="filter-wrapper mb-3">
                        <label>Domain:</label>
                        <select id="domain-filter" style="min-width:350px">
                            <option value="all" {{ $domainFilter == 'all' ? 'selected' : '' }}>All</option>
                            @foreach( $mailgunApiKeys as $mailgunApiKey )
                                <option value="{{ $mailgunApiKey->domain }}" {{ $domainFilter == $mailgunApiKey->domain ? 'selected' : '' }}>
                                    {{ $mailgunApiKey->domain }} <span>({{ $mailgunApiKey->totalCount() }})</span>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#failed-mails-wrapper" role="tab">Failed Mailgun Emails : {{ $totalFailedMailsCount }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#suppressions-wrapper" role="tab">Suppressions : {{ $totalSuppressionsCount }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="failed-mails-wrapper">
                            <div class="table-responsive">
                                <table class="table m-0" id="failed-mails-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="150px">Timestamp</th>
                                            <th>Domain</th>
                                            <th width="140px">Event</th>
                                            <th>Sender</th>
                                            <th>Recipient</th>
                                            <th>Website Name</th>
                                            <th width="120px"><button type="button" class="btn btn-info archive-all-mail-btn">Archive All</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="suppressions-wrapper">
                            <div class="table-responsive">
                                <table class="table m-0" id="supressions-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Domain</th>
                                            <th>Type</th>
                                            <th>Recipient</th>
                                            <th style="min-width:140px">Timestamp</th>
                                            <th>Description</th>
                                            <th style="min-width:150px"><button type="button" class="btn btn-info archive-all-suppression-btn">Archive All</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('manage-mailgun.modals.delete-suppression')
    @include('manage-mailgun.modals.archive-all-mail')
    @include('manage-mailgun.modals.archive-all-suppression')
@stop

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">
    <style>
        .filter-wrapper .select2-container{
            display: inline-block;
            margin-left: 5px;
        }
        div.dataTables_wrapper div.dataTables_processing {
            position: fixed !important;
            top: calc(50vh - 20px) !important;
        }
    </style>
@endsection


@section('javascript')
    <script>
        var allWebsites = {!! json_encode($allWebsites) !!};
        var domainFilter = "{{ $domainFilter }}";
    </script>
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/failed-mails-list.js?v=7') }}"></script>
@endsection
