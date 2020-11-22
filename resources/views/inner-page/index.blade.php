@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">

                    <h3 class="card-title"><i class="fa fa-tasks"></i> Jobs To Do : {{ $jobsToDoCount }}</h3>
                    <div class="filter-select-wrapper">
                        <select class="form-group blogFilter" id="blogFilter" style="width:120px;">
                            <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}> Pending </option>
                            <option value="completed" {{ $filter == 'completed' ? 'selected' : '' }}> Completed </option>
                        </select>
                    </div>
                    @can('content manager')
                        <div class="filter-select-wrapper">
                            <select class="form-control assigneeFilter" id="assigneeFilter" style="width:150px;">
                                <option value="-1" {{ $assigneeFilter == "-1" ? 'selected' : '' }}>All</option>
                                @foreach ($adminsWithTasks as $admin)
                                    <option value="{{ $admin->id }}" {{ $assigneeFilter == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan

                    <div class="card-tools pull-right">
                        @can('content manager')
                            <button id="add-task-button" type="button" class="btn btn-default pull-right">
                                <i class="fa fa-plus"></i> Add task
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @include('inner-page.sections.task-list', ['job_status' => 'to-do'])
                </div>
            </div>
        </div>
    </div>

    @if( $filter == 'pending' )
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <i class="fa fa-hourglass-o"></i>

                        <h3 class="card-title">Jobs On Hold</h3>
                    </div>
                    <div class="card-body">
                        @include('inner-page.sections.task-list', ['job_status' => 'on-hold'])
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!--Hidden Form Download All Files-->
    <form id="download-all-files-form" method="GET" action="{{ url('/inner-page-download-files') }}" target="_blank">
        @csrf
        <input type="hidden" class = "inner-blog-id" name="innerBlogId">
        <input type="hidden" class = "inner-blog-file-id" name="innerBlogFileId">
    </form>

    <!-- Modals -->
    @include('inner-page.modal.add-task')
    @include('inner-page.modal.delete-task')
    @include('inner-page.modal.complete-task')
    @include('inner-page.modal.clear-blog')
    @include('inner-page.modal.clear-image')
    @include('inner-page.modal.undo-complete')

@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/inner-page-list.css?v=14') }}" >
@endsection

@section('javascript')
    <script>
        var enableDrag = "{{ $enableDrag }}";
        var editInnerBlogId = {{ $editInnerBlogId }};
        var filter = "{{ $filter }}";
        var assinee = "{{ $assigneeFilter }}";
        var sortColumn = "{{ $sortColumn }}";
        var sortOrder = "{{ $sortOrder }}";
    </script>
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ mix('/js/download-adaptor.js') }}"></script>
    <script src="{{ asset('assets/js/lib/ckeditor.js?v=1') }}"></script>
    <script src="{{ asset('assets/js/inner-page-list.js?v=28') }}"></script>
@endsection
