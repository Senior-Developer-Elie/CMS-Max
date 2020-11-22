@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title" style="margin-left:10px;">Proposals List</h3>
                    <div class="card-tools">
                        <select id = "proposal-filter" class="form-control">
                            <option value="all" {{ $type != 'all' ? 'selected' : '' }}>View All</option>
                            <option value="not-signed" {{ $type == 'not-signed' ? 'selected' : '' }}>Not Signed</option>
                            <option value="signed" {{ $type == 'signed' ? 'selected' : '' }}>Signed</option>
                            <option value="not-sold" {{ $type == 'not-sold' ? 'selected' : '' }}>Not Sold</option>
                        </select>
                        <a href="{{ url('/add-proposal') }}">
                            <button type="button" class="btn btn-primary pull-right add-proposal-button">Add Proposal</button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if( $type == 'not-sold' )
                        <table id="proposal-list" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th width="200px">Status</th>
                                    <th>Created At</th>
                                    <th width = "75px">Download</th>
                                    <th width = "75px">Delete</th>
                                    <th width = "120px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    @if( !$proposal['sold'] )
                                        <tr>
                                            <td>
                                                <a href = "{{ url('edit-proposal?proposalId=' . $proposal['id']) }}" style="width:100%; display:block;">
                                                    {{ $proposal['request']['clientName'] }}
                                                </a>
                                            </td>
                                            <td>
                                                @if( $proposal['status'] == 'signed' )
                                                    <label class="label label-primary">Signed on {{ $proposal['signed_at'] ? (new DateTime($proposal['signed_at']))->format('M d, Y h:i A') : '' }}</label>
                                                @elseif( $proposal['status'] == 'manual-signed' )
                                                    <label class="label label-success">Manually Signed on {{ $proposal['signed_at'] ? (new DateTime($proposal['signed_at']))->format('M d, Y h:i A') : '' }}</label>
                                                @else
                                                    <label class="label label-danger">Not Signed</label>
                                                @endif
                                            </td>
                                            <td>{{ (new DateTime($proposal['created_at']))->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <form method="POST" action="{{ url("admin-download") }}" target="_blank" style="display:inline-block">
                                                    @csrf
                                                    <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                    <button type="submit" class="btn btn-primary">Download</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="{{ url("delete-proposal") }}" style="display:inline-block">
                                                    @csrf
                                                    <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ url("change-proposal-sold-status/" . $proposal['id'] . "/1") }}">
                                                    <button type="submit" class="btn btn-primary">Mark as Sold</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <table id="proposal-list" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th width="200px">Status</th>
                                    <th>Created At</th>
                                    <th width = "90px">Manual Sign</th>
                                    <th width = "75px">Send Email</th>
                                    <th width = "75px">Download</th>
                                    @can('delete ability')
                                        <th width = "75px">Delete</th>
                                    @endcan
                                    <th width = "120px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                    @if( $proposal['sold'] == true )
                                        <tr>
                                            <td>
                                                <a href = "{{ url('edit-proposal?proposalId=' . $proposal['id']) }}" style="width:100%; display:block;">
                                                    {{ $proposal['request']['clientName'] }}
                                                </a>
                                            </td>
                                            <td>
                                                @if( $proposal['status'] == 'signed' )
                                                    <label class="label label-primary">Signed on {{ $proposal['signed_at'] ? (new DateTime($proposal['signed_at']))->format('M d, Y h:i A') : '' }}</label>
                                                @elseif( $proposal['status'] == 'manual-signed' )
                                                    <label class="label label-success">Manually Signed on {{ $proposal['signed_at'] ? (new DateTime($proposal['signed_at']))->format('M d, Y h:i A') : '' }}</label>
                                                @else
                                                    <label class="label label-danger">Not Signed</label>
                                                @endif
                                            </td>
                                            <td>{{ (new DateTime($proposal['created_at']))->format('M d, Y h:i A') }}</td>
                                            <td style="text-align: center">
                                                @if( $proposal['status'] == 'not-signed' )
                                                    <form method="GET" action="{{ url("manual-sign") }}" style="display:inline-block">
                                                        <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                        <button type="submit" class="btn btn-success">Manual Sign</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                @if( $proposal['status'] == 'not-signed' )
                                                    <form method="GET" action="{{ url("send-email") }}" style="display:inline-block">
                                                        <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                        <button type="submit" class="btn btn-info">Send Email</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ url("admin-download") }}" target="_blank" style="display:inline-block">
                                                    @csrf
                                                    <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                    <button type="submit" class="btn btn-primary">Download</button>
                                                </form>
                                            </td>
                                            @can('delete ability')
                                                <td>
                                                    <form method="GET" action="{{ url("delete-proposal") }}" style="display:inline-block">
                                                        @csrf
                                                        <input type="hidden" name="proposalId" value="{{ $proposal['id'] }}">
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            @endcan
                                            <td>
                                                <a href="{{ url("change-proposal-sold-status/" . $proposal['id'] . "/0") }}">
                                                    <button type="submit" class="btn btn-warning">Mark as Not Sold</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/proposal-list.css?v=1') }}">
@endsection

@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/proposal-list.js') }}">
    </script>
@endsection
