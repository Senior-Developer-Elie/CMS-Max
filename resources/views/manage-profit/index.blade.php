@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Profits</h3>
                    <div class="bod-tools">
                        <button type="button" class="btn btn-info pull-right" id = "add-profit-button">Add Profit</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th width="160px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $profits as $profit )
                                <tr data-profit-id = {{ $profit->id }}>
                                    <td> {{ $profit->name }}</td>
                                    <td> ${{ $profit->price }}</td>
                                    <td> {!! $profit->description !!}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary edit-button">Edit</button>
                                        @if( $profit->key == '' && Auth::user()->can('delete ability'))
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
    @include('manage-profit.modals.add-profit')
    @include('manage-profit.modals.delete-profit')
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
    <script src="{{ asset('assets/js/profit.js?v=1') }}"></script>
@endsection
