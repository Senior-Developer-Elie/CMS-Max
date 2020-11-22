@extends('layouts.theme')

@section('content-header')
    <h1>
        Confirm Completion of Blog
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
                        <div class="form-group">
                            <label for="blog-website">Blog Url</label>
                            <input type="text" class="form-control" id="blog-website" name="blog_website" placeholder="Enter blog url" required>
                        </div>
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
