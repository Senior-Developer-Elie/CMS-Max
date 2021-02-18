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

                    <div class="card-tools pull-right">
                        <a href="{{ route('websites.create') }}">
                            <button type="button" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add Website
                            </button>
                        </a>
                        <button id="export-websites-budget-button" type="button" class="btn btn-info pull-right mr-3">
                            <i class="fas fa-download"></i> Export Websites Budget
                        </button>
                    </div>
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

    <form id="websites-budget-export-form" role="form" action="{{ route("websites.export-budget") }}" target="_blank" method="POST" style="display:none;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
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
        var allWebsiteTypes = {!! json_encode($websiteTypes) !!};
        var allAffiliateTypes = {!! json_encode($affiliateTypes) !!};
        var allDNSTypes = {!! json_encode($dnsTypes) !!};
        var allPaymentGateways = {!! json_encode($paymentGateways) !!};
        var allEmailTypes = {!! json_encode($emailTypes) !!};
        var allSitemapTypes = {!! json_encode($sitemapTypes) !!};
        var allLeftReviewTypes = {!! json_encode($leftReviewTypes) !!};
        var allPortfolioTypes = {!! json_encode($portfolioTypes) !!};
        var allShippingMethodTypes = {!! json_encode($shippingMethodTypes) !!};
        var allYextTypes = {!! json_encode($yextTypes) !!};
        var allIndustries = {!! json_encode($allIndustries) !!};
    </script>

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/website-list.js?v=46') }}"></script>
@endsection
