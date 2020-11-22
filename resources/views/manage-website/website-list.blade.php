@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Website List</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Websites : {{ count($websites) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#archived-websites-wrapper" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Archived Websites : {{ count($archivedWebsites) }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" role="tabpanel" id="websites-wrapper">
                            @include('manage-website.sections.website-table', [ 'websites' => $websites, 'archived' => false ])
                        </div>
                        <div class="tab-pane fade show" role="tabpanel" id="archived-websites-wrapper">
                            @include('manage-website.sections.website-table', [ 'websites' => $archivedWebsites, 'archived' => true ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("manage-client.modals.add-website")
    @include("manage-client.modals.delete-website")
    @include("manage-client.modals.archive-website")

@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <!-- Jquery Editable -->
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/website-list.css?v=6') }}">
@endsection
@section('javascript')
    <script>
        var allWebsiteTypes = {!! json_encode($allWebsiteTypes) !!};
        var allAffiliateTypes = {!! json_encode($allAffiliateTypes) !!};
        var allDNSTypes = {!! json_encode($allDNSTypes) !!};
        var allPaymentGateways = {!! json_encode($allPaymentGateways) !!};
        var allEmailTypes = {!! json_encode($allEmailTypes) !!};
        var allSitemapTypes = {!! json_encode($allSitemapTypes) !!};
        var allLeftReviewTypes = {!! json_encode($allLeftReviewTypes) !!};
        var allPortfolioTypes = {!! json_encode($allPortfolioTypes) !!};
        var allShippingMethodTypes = {!! json_encode($allShippingMethodTypes) !!};
        var allYextTypes = {!! json_encode($allYextTypes) !!};
        var allIndustries = {!! json_encode($allIndustries) !!};
    </script>

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/website-add-edit-modal.js?v=9') }}"></script>
    <script src="{{ asset('assets/js/website/website-list.js?v=43') }}"></script>
@endsection
