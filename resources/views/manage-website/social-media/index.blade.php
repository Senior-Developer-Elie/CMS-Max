@extends('layouts.theme')

@section('content-header')
    <div class="mt-2">
        <select id="websites-status-filter" style="width:200px;">
            <option value="active" {{ $statusFilter == "active" ? 'selected' : '' }}>Active Websites</option>
            <option value="inactive" {{ $statusFilter == "inactive" ? 'selected' : '' }}>Inactive Websites</option>
        </select>
    </div>
@endsection

@if ($statusFilter == 'active')
    @include('manage-website.social-media.active-list')
@else
    @include('manage-website.social-media.inactive-list')
@endif
