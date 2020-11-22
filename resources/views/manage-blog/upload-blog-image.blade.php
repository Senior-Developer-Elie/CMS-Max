@extends('layouts.theme')

@section('content-header')
    <h1>
        Upload Blog Image
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{ $blog->name }} </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="blogImageFile">Blog Image File</label>
                            <input type="file" class="form-control" id="blogImageFile" name="blogImageFile[]" accept="image/*" multiple>
                            @if($blog->marked)
                                <span class="help-block">This blog is already completed. </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success pull-right">Upload</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
