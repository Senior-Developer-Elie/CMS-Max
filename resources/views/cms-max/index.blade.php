@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>
                    <h3 class="card-title">CMS-Max</h3>
                </div>
                <div class="card-body">
                    <div class="tab-pane fade show" role="tabpanel" id="archived-websites-wrapper">
                        @include('manage-cms-max.sections.cms-max-table', [ 'websites' => $websites ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <!-- DataTables -->
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <!-- Jquery Editable -->
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/cms-max-list.css?v=10') }}">

@endsection

@section('javascript')

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/cms-max/cms-max-list.js?v=51') }}"></script>
@endsection