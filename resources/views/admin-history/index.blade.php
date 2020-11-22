@extends('layouts.theme')

@section('content-header')
<h3>Admin History</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <label>Admin: </label>
                    <select id = "client-filter" class="form-control">
                        <option value="all" {{ $userId == 'all' ? 'selected' : '' }}>All</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <table id = "admin-history-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Log</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adminHistories as $adminHistory)
                                <tr>
                                    <td>
                                        {{ (new \Carbon\Carbon($adminHistory->created_at))->format('m/d/Y h:i a') }}
                                    </td>
                                    <td>
                                        {{ is_null($adminHistory->user()) ? '' : $adminHistory->user()->name }}
                                    </td>
                                    <td>
                                        {!! App\Http\Helpers\AdminHistoryHelper::getHistoryText($adminHistory) !!}
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
    <!-- DataTables -->
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-history.css?v=1') }}">
@endsection

@section('javascript')
    <!-- DataTables -->
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/admin-history.js?v=1') }}"></script>
@endsection
