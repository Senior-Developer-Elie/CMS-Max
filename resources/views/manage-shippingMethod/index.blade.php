@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage ShippingMethods</h3>
                    <div class="bod-tools">
                        <button type="button" class="btn btn-info pull-right" id = "add-shippingMethod-button">Add ShippingMethod</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th width="160px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $shippingMethods as $shippingMethod )
                                <tr data-shippingMethod-id = {{ $shippingMethod->id }}>
                                    <td> {{ $shippingMethod->name }}</td>
                                    <td> {!! $shippingMethod->description !!}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary edit-button">Edit</button>
                                        @if( Auth::user()->can('delete ability'))
                                            <button type="button" class="btn btn-danger delete-button" style="margin-left: 10px;">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('manage-shippingMethod.modals.add-shippingMethod')
    @include('manage-shippingMethod.modals.delete-shippingMethod')
@endsection
@section('css')
    <style>
        table.table td{
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('javascript')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.0/classic/ckeditor.js"></script>
    <script src="{{ asset('assets/js/shippingMethod.js?v=1') }}"></script>
@endsection
