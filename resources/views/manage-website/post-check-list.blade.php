@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Websites Post Live Checklist</h3>
                </div>
                <div class="card-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#active-websites" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Active Websites : {{ count($activeWebsites) }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#completed-websites" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Completed Websites : {{ count($completedWebsites) }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" role="tabpanel" id="active-websites">
                                @include('manage-website.sections.post-websites-list', [ 'tableId'  => 'active-websites-table', 'websites' => $activeWebsites ])
                            </div>
                            <div class="tab-pane fade show" role="tabpanel" id="completed-websites">
                                @include('manage-website.sections.post-websites-list', [ 'tableId'  => 'completed-websites-table', 'websites' => $completedWebsites ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("manage-client.modals.archive-website")
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <!-- Jquery Editable -->
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/post-live-checklist.css?v=5') }}">
@endsection
@section('javascript')
    <script>
        var allPostLiveOptions = {!! json_encode($allPostLiveOptions) !!};
    </script>
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/post-live-checklist.js?v=10') }}"></script>
@endsection
