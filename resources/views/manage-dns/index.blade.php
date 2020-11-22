@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Dns</h3>
                    <div class="bod-tools">
                        <button type="button" class="btn btn-info pull-right" id = "add-dns-button">Add Dns</button>
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
                            @foreach ( $dnss as $dns )
                                <tr data-dns-id = {{ $dns->id }}>
                                    <td> {{ $dns->name }}</td>
                                    <td> {!! $dns->description !!}</td>
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
    @include('manage-dns.modals.add-dns')
    @include('manage-dns.modals.delete-dns')
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
    <script src="{{ asset('assets/js/dns.js?v=1') }}"></script>
@endsection
