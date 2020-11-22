@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Api Client List</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#pending-api-clients" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pending : {{ count($pendingApiClientIds) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#archived-api-clients" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Archived : {{ count($archivedApiClientIds) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="pending-api-clients">
                            <table id = "pending-websites-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="15px"><input type="checkbox" class="check-all"></th>
                                        <th>Name</th>
                                        <th>Website</th>
                                        <th>Address</th>
                                        <th width="130px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingApiClientIds as $apiClientId)
                                        @if (isset($apiClients[$apiClientId]))
                                            <tr data-api-client-id={{ $apiClients[$apiClientId]['id'] }}>
                                                <td  class="comp-check">
                                                    <input type="checkbox" class="api-client-check">
                                                </td>
                                                <td>
                                                    {{ $apiClients[$apiClientId]['name'] }}
                                                </td>
                                                <td>
                                                    @if( isset($apiClients[$apiClientId]['website']) && strlen($apiClients[$apiClientId]['website']) > 0 )
                                                        <a href="//{{ getCleanUrl($apiClients[$apiClientId]['website']) }}" target="_blank" data-value="{{ getCleanUrl($apiClients[$apiClientId]['website']) }}">
                                                            {{ getCleanUrl($apiClients[$apiClientId]['website']) }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $apiClients[$apiClientId]['address1'] }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning pull-left archive-btn">Archive Client</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @if( count($pendingApiClientIds) > 0 )
                                        @can('content manager')
                                            <button type="button" id = "add-api-clients" class="btn btn-primary pull-right">Sync Selected</button>
                                        @endcan
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" id="archived-api-clients">
                            <table id = "archived-websites-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Website</th>
                                        <th>Address</th>
                                        <th width="130px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($archivedApiClientIds as $apiClientId)
                                        @if (isset($apiClients[$apiClientId]))
                                            <tr data-api-client-id={{ $apiClients[$apiClientId]['id'] }}>
                                                <td>
                                                    {{ $apiClients[$apiClientId]['name'] }}
                                                </td>
                                                <td>
                                                    @if( isset($apiClients[$apiClientId]['website']) && strlen($apiClients[$apiClientId]['website']) > 0 )
                                                        <a href="//{{ getCleanUrl($apiClients[$apiClientId]['website']) }}" target="_blank" data-value="{{ getCleanUrl($apiClients[$apiClientId]['website']) }}">
                                                            {{ getCleanUrl($apiClients[$apiClientId]['website']) }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $apiClients[$apiClientId]['address1'] }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info pull-left enable-btn">Enable Client</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('manage-client.modals.sync-all')
    @include('manage-client.modals.archive-api-client')
    @include('manage-client.modals.unarchive-api-client')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/api-client-list.css?v=1') }}">
@endsection
@section('javascript')
    <!-- DataTables -->
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/client/api-client-list.js?v=5') }}"></script>
@endsection
