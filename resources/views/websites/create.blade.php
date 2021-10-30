@extends('layouts.theme')

@section('content-header')
    <h3>Add Website</h3>
@endsection

@section('content')
    @include('partials.form-errors')

    <form role="form" action="{{ route("websites.store") }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-lg-9">

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website-name">Website Name</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website-url">Website Url</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="website" value="{{ old('website') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="target-area">Target Area</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="target_area" value="{{ old('target_area') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Industry</label>
                                    <div class="">
                                        <select class="form-control" name="blog_industry_id" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($blogIndustries as $blogIndustry)
                                                <option value="{{ $blogIndustry->id }}" {{ old_selected('blog_industry_id', $blogIndustry->id) }}>{{ $blogIndustry->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Website Type</label>
                                    <div class="">
                                        <select class="form-control" name="type" style="width: 100%;">
                                            @foreach ($websiteTypes as $websiteTypeId=>$name)
                                                <option value="{{ $websiteTypeId }}" {{ old_selected('type', $websiteTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Completed Date:</label>
                                    <div class="">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control pull-right" name="completed_at" value="{{ old('completed_at') ? (\Carbon\Carbon::parse(old('completed_at'))->format('m/d/Y')) : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Merchant Center</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="merchant_center" value="{{ old('merchant_center') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Flow Chart</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="flow_chart" value="{{ old('flow_chart') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="drive">Google Drive</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="drive" value="{{ old('drive') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="social_calendar">Social Calendar</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="social_calendar" value="{{ old('social_calendar') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="uses_our_credit_card" {{ old_checked('uses_our_credit_card') }}>
                                        <strong class="ml-1">Uses our Credit Card</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Social Links</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin_url">LinkedIn</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="linkedin_url" value="{{ old('linkedin_url') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url">YouTube</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="youtube_url" value="{{ old('youtube_url') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_url">Twitter</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="twitter_url" value="{{ old('twitter_url') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_url">Facebook</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="facebook_url" value="{{ old('facebook_url') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_url">Instagram</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="instagram_url" value="{{ old('instagram_url') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pinterest_url">Pinterest</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="pinterest_url" value="{{ old('pinterest_url') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tik_tok_url">TikTok</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="tik_tok_url" value="{{ old('tik_tok_url') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Attributes</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Affiliate</label>
                                    <div class="">
                                        <select class="form-control" name="affiliate" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($affiliateTypes as $affiliateTypeId=>$name)
                                                <option value="{{ $affiliateTypeId }}" {{ old_selected('affiliate', $affiliateTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">DNS</label>
                                    <div class="">
                                        <select class="form-control" name="dns" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($dnsTypes as $dnsType)
                                                <option value="{{ $dnsType['value'] }}" {{ old_selected('dns', $dnsType['value']) }}>
                                                    {{ $dnsType['text'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Email</label>
                                    <div class="">
                                        <select class="form-control" name="email" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($emailTypes as $emailTypeId => $name)
                                                <option value="{{ $emailTypeId }}" {{ old_selected('email', $emailTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Portfolio</label>
                                    <div class="">
                                        <select class="form-control" name="on_portfolio" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($portfolioTypes as $portfolioTypeId => $name)
                                                <option value="{{ $portfolioTypeId }}" {{ old_selected('on_portfolio', $portfolioTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Sitemap</label>
                                    <div class="">
                                        <select class="form-control" name="sitemap" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($sitemapTypes as $sitemapTypeId => $name)
                                                <option value="{{ $sitemapTypeId }}" {{ old_selected('sitemap', $sitemapTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog-industry">Left Review</label>
                                    <div class="">
                                        <select class="form-control" name="left_review" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($leftReviewTypes as $leftReviewTypeId => $name)
                                                <option value="{{ $leftReviewTypeId }}" {{ old_selected('left_review', $leftReviewTypeId) }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="blog-industry">Payment Gateway</label>
                            <div class="">
                                <select class="form-control" name="payment_gateway[]" multiple="multiple" data-placeholder="Select Payment Gateways" style="width: 100%;">
                                    @foreach ($paymentGateways as $paymentGatewayId => $name)
                                        <option value="{{ $paymentGatewayId }}" {{ \in_array($paymentGatewayId, old('payment_gateway', [])) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!--TSYS Fields-->
                        <div class="tsys-fields-wrapper">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mid">MID</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="mid" value="{{ old('mid') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-scan-user">Control Scan User</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="control_scan_user" value="{{ old('control_scan_user') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-scan-pass">Control Scan Pass</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="control_scan_pass" value="{{ old('control_scan_pass') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Control Scan Renewal Date:</label>
                                        <div class="">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="control_scan_renewal_date" 
                                                    value="{{ old('control_scan_renewal_date') ? (\Carbon\Carbon::parse(old('control_scan_renewal_date'))->format('m/d/Y')) : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Product Fees</h3>
                    </div>
                    <div class="card-body">
                        <website-product-fees 
                            :initial-sync-from-client="{{ old('sync_from_client', true) ? 'true' : 'false' }}"
                            :products="{{ json_encode(\App\AngelInvoice::products()) }}"
                            :initial-website-products="{{ json_encode($websiteProducts) }}"
                            :crm-product-keys-with-additional-values="{{ json_encode(\App\AngelInvoice::crmProductKeysWithAdditionalValues()) }}"
                        />
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Save</h3>
                    </div>
                    <div class="card-body clearfix">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Client</h3>
                    </div>
                    <div class="card-body clearfix">
                        <div class="form-group">
                            <div class="">
                                <select class="form-control" name="client_id">
                                    <option value=""></option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ old_selected('client_id', $client->id, Request::input('client_id')) }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Blog Details</h3>
                    </div>
                    <div class="card-body clearfix">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_blog_client" {{ old_checked('is_blog_client') }}><strong>Enable Blog</strong>
                            </label>
                        </div>
                        
                        <div id="client-detail-info-wrapper">
                            <div class="form-group">
                                <label for="admin-list">Writer</label>
                                <div class="">
                                    <select class="form-control" name="assignee_id" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old_selected('assignee_id', $admin->id) }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Blog Frequency</label>
                                <div class="">
                                    <select class="form-control" name="frequency" style="width: 100%">
                                        <option value="" ></option>
                                        @foreach ($blogFrequencies as $key => $name)
                                            <option value="{{ $key }}" {{ old_selected('frequency', $key) }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Start Date:</label>
                                <div class="">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input 
                                            type="text"
                                            class="form-control pull-right"
                                            name="start_date"
                                            value="{{ old('start_date') ? (\Carbon\Carbon::parse(old('start_date'))->format('m/Y')) : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/website/add-website.js?version=2') }}"></script>
@endsection