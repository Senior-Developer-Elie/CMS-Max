@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">

        <div class="col-md-3 col-sm-6 col-md-12 custom-col-2-5">
            <a href = "{{ url('blog-list?blogType=pendingToAddTitle') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fas fa-pencil-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Blogs to Add Title</span>
                        <span id = "pending-blogs-to-write-count" class="info-box-number">{{ $pendingBlogsToAddTitle }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-md-12 custom-col-2-5">
            <a href = "{{ url('blog-list?blogType=pendingToWrite') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fas fa-feather-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Blogs to Write</span>
                        <span id = "pending-blogs-to-write-count" class="info-box-number">{{ $pendingBlogsToWrite }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-md-12 custom-col-2-5">
            <a href = "{{ url('blog-list?blogType=pendingToAddImage') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-teal"><i class="fas fa-images"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Blogs to Add Image</span>
                        <span id = "pending-blogs-to-write-count" class="info-box-number">{{ $pendingBlogsToAddImage }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-md-12 custom-col-2-5">
            <a href = "{{ url('blog-list?blogType=pendingToAddToWebsite') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-images"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pending Blogs to Add to Website</span>
                        <span class="info-box-number">{{ $pendingBlogsToAddToWebsite }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-md-12 custom-col-2-5">
            <a href = "{{ url('blog-list?blogType=done') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Blogs Done</span>
                        <span class="info-box-number">{{ $blogsDone }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <select id="writers-filter" class="form-control" style="width: 150px;">
                <option value="all" {{ $filterUserId == 'all' ? 'selected' : '' }}>All Writers</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $filterUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            <table id="client-list" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="250px">Website Name ({{ count($websites) }})</th>
                        <th width="80px">Frequency</th>
                        @can('content manager')
                            <th>
                                Writer
                            </th>
                        @endcan
                        <th width="120px">Project Manager</th>
                        @foreach ($futureMonths as $index => $month)
                            <th class = "text-center month-name-wrapper" width="350px">
                                <span href="#" class="month-info-icon" data-toggle="tooltip" data-placement="top" title="Total Blogs for the month: {{ $totalBlogsForMonth[$index] }}">
                                    {{ $month->format('M') }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($websites as $website)
                        <tr data-website-id="{{ $website['website']->id }}">
                            <td class="client-name-wrapper dropdown">
                                <a class="client-name" href="{{ url('client-history?clientId=' . $website['website']->client()->id) }}">
                                    <span data-toggle="tooltip" data-placement="top" title="View Client">{{ $website['website']->name }}</span>
                                </a>
                                {{ $website['website']->target_area }}
                                <a href="//{{ $website['website']->website }}" target="_blank" class="client-info-icon" data-toggle="tooltip" data-placement="top" title="{{ $website['website']->website }}">
                                    <i class="fa fa-info-circle"></i>
                                </a>
                            </td>
                            <td style="text-transform:capitalize">
                                {{ $website['website']->frequency }}
                            </td>

                            @can('content manager')
                                <td>{{ is_null($website['website']->assignee()) ? '' : $website['website']->assignee()->name }}</td>
                            @endcan

                            <td>
                                @if ($website['website']->client()->projectManager)
                                    {{ $website['website']->client()->projectManager->name }}
                                @endif
                            </td>
                            @foreach ( $website['futureBlogs'] as $index => $blog )
                                <td class="blog-cell {{ $blog['class'] }}" data-desired-date="{{ $futureMonths[$index] }}">
                                    <?php
                                        $orderValue = 0;
                                        if($blog['class'] == 'empty')
                                            $orderValue = 0;
                                        else if($blog['class'] == 'normal')
                                            $orderValue = 1;
                                        else if($blog['class'] == 'pending-to-add-image')
                                            $orderValue = 2;
                                        else if($blog['class'] == 'pending')
                                            $orderValue = 3;
                                        else if($blog['class'] == 'done')
                                            $orderValue = 4;
                                        else
                                            $orderValue = 5;
                                    ?>
                                    <div data-order-value="{{ $orderValue }}">
                                        <span class="blog-name" data-blog-name="{{ $blog['blogName'] }}">
                                            {{ $blog['blogName'] }}
                                            @if ($blog['class'] == 'done')
                                                <br />
                                                <a href="{{ $blog['blogWebsite'] }}" target="_blank">
                                                    {{ $blog['blogTitle'] }}
                                                </a>
                                            @endif
                                        </span>
                                        <div class="blog-name-input-wrapper input-group input-group-sm" style="display:none">
                                            <input type="text" class="form-control">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger btn-flat blog-name-confirm">Go!</button>
                                            </span>
                                        </div>
                                        <div class="upload-button-wrapper">
                                            @if( in_array($blog['class'], ['normal']) )

                                                <a href="#" data-blog-id="{{ $blog['blog']->id }}" class="upload-blog-button" data-toggle="tooltip" data-placement="top" title="Upload Word File">
                                                    <img src="{{ asset('assets/images/iconfinder_word_272702.svg') }}">
                                                </a>
                                            @endif
                                            @if( in_array($blog['class'], ['pending-to-add-image']) )
                                                <a href="#" data-blog-id="{{ $blog['blog']->id }}" class="upload-image-button" data-toggle="tooltip" data-placement="top"  title="Upload Image">
                                                    <img src="{{ asset('assets/images/iconfinder_image_272698.svg') }}">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!--Hidden Input Fields For Upload -->
        <input type="file" id="blogFile" name="blogFile" style="display:none">
        <input type="file" id="blogImageFile" name="blogImageFile[]" accept="image/*" multiple style="display:none">
    </div>
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/blog-dashboard.css?v=3') }}">
@endsection

@section('javascript')
    <script>
        var isBlogManager = {{ $isBlogManager ? 'true' : 'false' }};
    </script>

    <!-- DataTables -->
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/blog-dashboard.js?v=5') }}"></script>
    <script src="{{ asset('assets/js/upload-action.js?v=3') }}"></script>
@endsection
