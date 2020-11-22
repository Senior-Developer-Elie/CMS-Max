@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mailgun Api Keys</h3>
                    <div class="bod-tools">
                        <button type="button" class="btn btn-info pull-right" id = "add-api-key-btn">Add Api Key</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Api Key</th>
                                <th width="160px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $mailgunApiKeys as $apiKey )
                                <tr class="api-key-row" data-mailgun-api-key-id = {{ $apiKey->id }}>
                                    <td class="domain-value" data-value="{{ $apiKey->domain }}"> {{ $apiKey->domain }}</td>
                                    <td class="key-value" data-value="{{ $apiKey->key }}"> {{ $apiKey->key }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary edit-button">Edit</button>
                                        @if( Auth::user()->can('delete ability') )
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
    @include('manage-mailgun.modals.add-api-key')
    @include('manage-mailgun.modals.delete-api-key')
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/manage-api-key.js') }}"></script>
@endsection
