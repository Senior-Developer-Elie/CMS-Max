@extends('layouts.theme')

@section('content-header')
    @if (in_array(Auth::user()->id, [1, 2]))
        <div class="row social-media-page__filter-wrapper">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                    <span class="info-box-text">Total Ad Spend</span>
                    <span class="info-box-number">$ {{ prettyFloat($totalAdSpend) }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                    <span class="info-box-text">Total Management Fee</span>
                    <span class="info-box-number">$ {{ prettyFloat($totalManagementFee) }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
    @endif
    <select id="websites-status-filter" style="width:200px;">
        <option value="active" {{ $statusFilter == "active" ? 'selected' : '' }}>Active Websites</option>
        <option value="inactive" {{ $statusFilter == "inactive" ? 'selected' : '' }}>Inactive Websites</option>
    </select>
@endsection

@if ($statusFilter == 'active')
    @include('manage-website.social-media.active-list')
@else
    @include('manage-website.social-media.inactive-list')
@endif
