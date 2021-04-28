@extends('layouts.theme')

@section('content-header')
    <h3 class="section-title">Website In Progress (<span id="total-task-count">{{ $totalTaskCount }}</span>)</h3>
    <select id="tasks-type-filter" style="width:150px">
        <option value="active" {{ $taskTypeFilter == "active" ? 'selected' : '' }}>Active</option>
        <option value="completed" {{ $taskTypeFilter == "completed" ? 'selected' : '' }}>Completed</option>
    </select>
@endsection

@section('content')
    <div id="tasks-list-wrapper" style="margin:0; {{ $isUniqueView ? 'display:none;' : '' }}">
        <div class="TaskHeader">
            <div class="TaskCell TaskNameCell" style="padding-left:20px">
                <div class="GridCell WithoutLeftBorder">
                    <label>
                        <strong>Task name</strong>
                    </label>
                </div>
            </div>
            <div class="TaskCell GridCell AssigneeCell">
                <strong>Assignee</strong>
            </div>
            <div class="TaskCell GridCell ProgressCell">
                <strong>Progress</strong>
            </div>
            @if( $taskTypeFilter == 'completed' )
                <div class="TaskCell GridCell CompletedAtCell">
                    <strong>Completed At</strong>
                </div>
            @endif
        </div>
        <div id="TaskGroupWrapper" class="scroll-bar-wrap">
            <div class="scroll-box">
                @foreach ( $stages as $stage )
                    @if( ($taskTypeFilter == "active" && $stage['name'] != 'Completed') OR
                        ($taskTypeFilter == "completed" && $stage['name'] == 'Completed') )
                        <div class="TaskGroup open" data-stage-id = {{ $stage->id }}>
                            <div class="TaskGroupHeader">
                                <svg class="MiniIcon DragMiniIcon TaskGroupHeader-dragMiniIcon" viewBox="0 0 24 24" visibility="hidden"><path d="M10,4c0,1.1-0.9,2-2,2S6,5.1,6,4s0.9-2,2-2S10,2.9,10,4z M16,2c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,2,16,2z M8,10 c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S9.1,10,8,10z M16,10c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,10,16,10z M8,18 c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S9.1,18,8,18z M16,18c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,18,16,18z"></path></svg>
                                <button class="TaskGroupHeader-toggleButton">
                                    <svg class="Icon LeftTriangleIcon" focusable="false" viewBox="0 0 32 32">
                                        <path d="M7.207,13.707L16.5,23l9.293-9.293c0.63-0.63,0.184-1.707-0.707-1.707H7.914C7.023,12,6.577,13.077,7.207,13.707z"></path>
                                    </svg>
                                    <svg class="Icon RightTriangleIcon" focusable="false" viewBox="0 0 32 32">
                                        <path d="M13.707,6.707L23,16l-9.293,9.293C13.077,25.923,12,25.477,12,24.586V7.414C12,6.523,13.077,6.077,13.707,6.707z"></path>
                                    </svg>
                                </button>
                                <div class="TaskGroupHeader-headerContainer">
                                    <div class="PotColumnName">
                                        <label class="PotColumnName-nameLabel">{{ $stage->name }} (<span class="stage-task-count">{{ count($stage->tasks()->get()) }}</span>)</label>
                                        @if( $stage->name != 'Completed' )
                                            <a href = "#" class="TaskHeaderAddTaskIcon" style="display:none;">
                                                <svg class="Icon" focusable="false" viewBox="0 0 32 32">
                                                    <path d="M26,14h-8V6c0-1.1-0.9-2-2-2l0,0c-1.1,0-2,0.9-2,2v8H6c-1.1,0-2,0.9-2,2l0,0c0,1.1,0.9,2,2,2h8v8c0,1.1,0.9,2,2,2l0,0c1.1,0,2-0.9,2-2v-8h8c1.1,0,2-0.9,2-2l0,0C28,14.9,27.1,14,26,14z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        {{--
                                        <a href = "#" class="TaskHeaderMenuIcon" style="display:none;">
                                            <svg class="Icon" focusable="false" viewBox="0 0 32 32">
                                                <path d="M16,13c1.7,0,3,1.3,3,3s-1.3,3-3,3s-3-1.3-3-3S14.3,13,16,13z M3,13c1.7,0,3,1.3,3,3s-1.3,3-3,3s-3-1.3-3-3S1.3,13,3,13z M29,13c1.7,0,3,1.3,3,3s-1.3,3-3,3s-3-1.3-3-3S27.3,13,29,13z"></path>
                                            </svg>
                                        </a>
                                        --}}
                                    </div>
                                </div>
                            </div>
                            <div class="TaskList">
                                @foreach ( $stage->tasks()->orderByDesc('priority')->get() as $task)
                                    @include('website-progress.sections.task-row', [ 'task' => $task ])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="cover-bar"></div>
        </div>
    </div>
    @include('website-progress.sections.task-details')
    @include('website-progress.sections.task-row', [ 'task' => false ])
    @include('website-progress.modals.delete-task')
    @include('website-progress.modals.complete-task')
    @include('website-progress.modals.add-comment')
    @include('website-progress.modals.delete-comment')
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/website-progress.css?v=33') }}">
@endsection

@section('javascript')
    <script>
        var allUsers            = {!! json_encode($allUsers) !!};
        var allMailHosts        = {!! json_encode($allMailHosts) !!};
        var allPreLiveOptions   = {!! json_encode($allPreLiveOptions) !!};
        var allClients          = {!! json_encode($allClients) !!};
        var activeTaskId        = {{ $activeTaskId }};
        var isUniqueView        = {{ $isUniqueView ? "true" : "false" }};
        var taskTypeFilter      = "{{ $taskTypeFilter }}";
        var userId              = {{ Auth::user()->id }};
    </script>
    <script src="{{ asset('assets/js/lib/ckeditor.js?v=2') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ mix('/js/download-adaptor.js') }}"></script>

    <script src="{{ asset('assets/js/website/website-progress.js?v=65') }}"></script>
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
@endsection
