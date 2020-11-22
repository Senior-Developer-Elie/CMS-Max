@extends('layouts.theme')

@section('content-header')
    <h1>
        Confirm Delete Client
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Delete Client : {{ $client->name }} </h3>
                    </div>
                    <div class="card-body">
                        Are you sure you want to delete this Client?
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
