@extends('layouts.theme')

@section('content-header')
    <h1>
        Confirm Proposal Manual Sign
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST" action="{{ url('manual-sign') }}">
                @csrf
                <input type = "hidden" name = "proposalId" value="{{ $proposal->id }}">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Manually Signing Proposal : {{ $proposal->request['clientName'] }} </h3>
                    </div>
                    <div class="card-body">
                        Are you sure you want to sign this proposal manually?
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-danger pull-right">Confirm</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
