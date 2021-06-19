@extends('layouts.theme')

@section('content-header')
    <h3>{{ $headingText }}</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="current-blogs-tab" data-toggle="pill" href="#current-blogs" role="tab" aria-controls="current-blogs" aria-selected="true">Current Month</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="future-blogs-tab" data-toggle="pill" href="#future-blogs" role="tab" aria-controls="future-blogs" aria-selected="false">Future</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="current-blogs" role="tabpanel" aria-labelledby="current-blogs-tab">
                            <table id="proposal-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Blog Title</th>
                                        <th>Client Name</th>
                                        <th>Website Name</th>
                                        <th>Website</th>
                                        <th>Target Area</th>
                                        <th width="300px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currentMonthBlogs as $blog)
                                        @include('manage-blog.sections.blog-row', ['blog'=>$blog])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="future-blogs" role="tabpanel" aria-labelledby="future-blogs-tab">
                            <table id="proposal-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Blog Title</th>
                                        <th>Client Name</th>
                                        <th>Website Name</th>
                                        <th>Website</th>
                                        <th>Target Area</th>
                                        <th width="300px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($futureMonthBlogs as $blog)
                                        @include('manage-blog.sections.blog-row', ['blog'=>$blog])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Hidden Input Fields For Upload -->
        <input type="file" id="blogFile" name="blogFile" style="display:none">
        <input type="file" id="blogImageFile" name="blogImageFile[]" accept="image/*" multiple style="display:none">
    </div>

    @include("manage-blog.modals.clear-uploaded-image")
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/client-history.css') }}">
    <style>
        button.btn{
            border: none;
        }
    </style>
@endsection

@section('javascript')
    <script src="{{ mix('/js/download-adaptor.js') }}"></script>
    <script src="{{ asset('assets/js/client-history.js?v=2') }}"></script>
    <script src="{{ asset('assets/js/upload-action.js?v=3') }}"></script>
@endsection
