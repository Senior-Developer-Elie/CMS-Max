@extends('layouts.theme')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('manage-client.sections.add-client')
        </div>
    </div>
    @if( isset($client) )
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#websites-wrapper" role="tab">Websites : {{ count($client->activeWebsites()->get()) }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#archived-websites-wrapper" role="tab">Archived Websites : {{ count($client->archivedWebsites()->get()) }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content no-border-tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" role="tabpanel" id="websites-wrapper">
                                @include('manage-website.sections.website-table', [ 'websites' => $client->activeWebsites()->orderBy('website')->get(), 'archived' => false ])
                            </div>
                            <div class="tab-pane fade show" role="tabpanel" id="archived-websites-wrapper">
                                @include('manage-website.sections.website-table', [ 'websites' => $client->archivedWebsites()->orderBy('website')->get(), 'archived' => true ])
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if( Auth::user()->hasRole('super admin') )
                            <a href="{{ route('websites.create', ['client_id' => $client->id]) }}">
                                <button type="button" class="btn btn-info pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Website
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#pending" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#completed" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Completed</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" role="tabpanel" id="pending">
                        <h4>Pending Blogs</h4>

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
                                @foreach ($pendingBlogs as $blog)
                                    @include('manage-blog.sections.blog-row', ['blog'=>$blog])
                                @endforeach
                            </tbody>
                        </table>
                        <h4>Pending Jobs</h4>
                        <table id="proposal-list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Assignee</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingJobs as $innerBlog)
                                    <tr>
                                        <td>
                                            {{ $innerBlog->title }}
                                        </td>
                                        <td>
                                            {{ (new \Carbon\Carbon($innerBlog->created_at))->format('m/d/Y h:i a') }}
                                        </td>
                                        <td>
                                            @if( $innerBlog->assignee() )
                                                <a href="{{ url('/admin-history?userId=' . $innerBlog->assignee()->id ) }}">
                                                    {{ $innerBlog->assignee()->name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td style="text-transform:capitalize">
                                            {{ $innerBlog->status() == "done" ? "Done" : "Pending" }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="tab-pane fade show" id="completed" role="tabpanel" >
                        <h4>Completed Blogs</h4>

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
                                @foreach ($completedBlogs as $blog)
                                    @include('manage-blog.sections.blog-row', ['blog'=>$blog])
                                @endforeach
                            </tbody>
                        </table>
                        <h4>Completed Jobs</h4>
                        <table id="proposal-list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Assignee</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($completedJobs as $innerBlog)
                                    <tr>
                                        <td>
                                            {{ $innerBlog->title }}
                                        </td>
                                        <td>
                                            {{ (new \Carbon\Carbon($innerBlog->created_at))->format('m/d/Y h:i a') }}
                                        </td>
                                        <td>
                                            @if( $innerBlog->assignee() )
                                                <a href="{{ url('/admin-history?userId=' . $innerBlog->assignee()->id ) }}">
                                                    {{ $innerBlog->assignee()->name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td style="text-transform:capitalize">
                                            {{ $innerBlog->status() == "done" ? "Done" : "Pending" }}
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

    <!--Hidden Input Fields For Upload -->
    <input type="file" id="blogFile" name="blogFile" style="display:none">
    <input type="file" id="blogImageFile" name="blogImageFile[]" accept="image/*" multiple style="display:none">

    @include("manage-blog.modals.clear-uploaded-image")
    @include("manage-client.modals.add-website")
    @include("manage-client.modals.delete-website")
    @include("manage-client.modals.archive-website")
@endsection

@section('css')

    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/client-history.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/client-add-edit.css?v=4') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/website-list.css?v=7') }}">
@endsection

@section('javascript')
    <script>
        var clientId = {{ isset($client) ? $client->id : "-1" }};
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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.0/classic/ckeditor.js"></script>

    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/client-history.js?v=2') }}"></script>
    <script src="{{ asset('assets/js/client/client-add-edit.js?v=15') }}"></script>
    <script src="{{ asset('assets/js/website/website-add-edit-modal.js?v=9') }}"></script>
    <script src="{{ asset('assets/js/website/website-list.js?v=48') }}"></script>
    <script src="{{ asset('assets/js/upload-action.js?v=3') }}"></script>
@endsection
