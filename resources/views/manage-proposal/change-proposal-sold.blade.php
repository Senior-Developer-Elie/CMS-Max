@extends('layouts.theme')

@section('content-header')
    <h1>
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Change Proposal To {{ $proposal->sold ? 'Not Sold' : 'Sold' }} : {{ $proposal->request['clientName'] }} </h3>
                    </div>
                    <div class="card-body">
                        Are you sure you want to change this proposal status?
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pull-right">Confirm</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
