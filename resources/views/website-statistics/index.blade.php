@extends('layouts.theme')

@section('content-header')
    <h3 class="section-title">History & Stats</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div id ="chart-wrapper" class="card card-success card-outline">
                <div class="card-header">
                  <h3 class="card-title">Website Builds</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="bar-chart col-10">
                            <canvas id="completionBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="pie-chart col-2">
                            <canvas id="yearlyPieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#live-websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Live Websites : {{ count($liveWebsites) + count($completedTasks)}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#no-websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">No Longer Websites : {{ count($noWebsites) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#redirect-websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Redirect Websites : {{ count($redirectWebsites) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="live-websites-wrapper">
                            <table id = "live-website-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Live Url</th>
                                        <th class="text-center">Date Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $completedTasks as $task )
                                        <tr data-row-type="task" data-id="{{ $task->id }}">
                                            <td>
                                                {{ $task->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ getCleanUrl($task->live_url) }}" target="_blank">
                                                    {{ getCleanUrl($task->live_url) }}
                                                </a>
                                            </td>
                                            <td class="text-center date-wrapper">
                                                <a class="completed-at-value" data-value={{ $task->completed_at }}>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach ( $liveWebsites as $website )
                                        <tr data-row-type="website" data-id="{{ $website->id }}">
                                            <td>
                                                {{ $website->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ getCleanUrl($website->website) }}" target="_blank">
                                                    {{ getCleanUrl($website->website) }}
                                                </a>
                                            </td>
                                            <td class="text-center date-wrapper">
                                                <a class="completed-at-value" data-value={{ $website->completed_at }}>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" role="tabpanel" id="no-websites-wrapper">
                            <table id = "no-website-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Live Url</th>
                                        <th class="text-center">Date Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $noWebsites as $website )
                                        <tr data-row-type="website" data-id="{{ $website->id }}">
                                            <td>
                                                {{ $website->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ getCleanUrl($website->website) }}" target="_blank">
                                                    {{ getCleanUrl($website->website) }}
                                                </a>
                                            </td>
                                            <td class="text-center date-wrapper">
                                                <a class="completed-at-value" data-value={{ $website->completed_at }}>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" role="tabpanel" id="redirect-websites-wrapper">
                            <table id = "redirect-website-table" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Website Name</th>
                                        <th>Live Url</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $redirectWebsites as $website )
                                        <tr data-row-type="website" data-id="{{ $website->id }}">
                                            <td>
                                                {{ $website->name }}
                                            </td>
                                            <td>
                                                <a href = "//{{ getCleanUrl($website->website) }}" target="_blank">
                                                    {{ getCleanUrl($website->website) }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/website-statistics.css?v=2') }}">
@endsection

@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ mix('js/chart.js') }}"></script>

    <script src="{{ asset('assets/js/website/website-statistics.js?v=14') }}"></script>
@endsection
