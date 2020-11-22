@extends('layouts.theme')

@section('content-header')
    <h1>
        Clear Uploaded Files Of Blog
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Blog : {{ $blog->name }} </h3>
                    </div>
                    <div class="card-body">
                        Are you sure you want to clear uploaded blog and images of this blog?
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success pull-right">Confirm</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
