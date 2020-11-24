@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-10 col-md-10 col-xl-6">
            @include('manage-client.sections.add-client')
        </div>
    </div>
@endsection

@section('css')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/client-add-edit.css?v=2') }}">
@endsection

@section('javascript')
    <!-- bootstrap datepicker -->
    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.0/classic/ckeditor.js"></script>
    <script src="{{ asset('assets/js/client/client-add-edit.js?v=11') }}"></script>
@endsection

