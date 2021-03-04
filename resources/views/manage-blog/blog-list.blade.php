@extends('layouts.theme')

@section('content-header')
    <h3>{{ $headingText }}</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="proposal-list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                @if( count($emptyBlogs) == 0 )
                                    <th>Blog Title</th>
                                @endif
                                <th>Client Name</th>
                                <th>Website Name</th>
                                <th>Website</th>
                                <th>Target Area</th>

                                @if( count($emptyBlogs) == 0 )
                                    <th width="300px">Status</th>
                                @else
                                    <th>Writer</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $blog)
                                @include('manage-blog.sections.blog-row', ['blog'=>$blog])
                            @endforeach

                            @foreach ($emptyBlogs as $blog)
                                <tr>
                                    <td>
                                        {{ (new \Carbon\Carbon($blog['desired_date']))->format('M Y') }}
                                    </td>
                                    <td>
                                        <a href = "{{ url('/client-history?clientId=' . $blog['website']->client()->id) }}">
                                            {{ $blog['website']->client()->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $blog['website']->name }}
                                    </td>
                                    <td>
                                        <a href = "//{{ $blog['website']->website }}" target="_blank">
                                            {{ $blog['website']->website }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $blog['website']->target_area }}
                                    </td>
                                    <td>
                                        {{ is_null($blog['website']->assignee()) ? '' : $blog['website']->assignee()->name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
