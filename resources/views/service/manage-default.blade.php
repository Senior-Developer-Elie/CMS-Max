@extends('layouts.theme')
@section('content-header')
    <h1>
        Manage Default Text and Price For Services/Products
    </h1>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Services</h3>
                    <div class="card-tools">
                        <a href="{{ url('add-service') }}">
                            <button type="button" class="btn btn-primary mb-1"><i class = "fa fa-plus"></i></button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="services-list" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Content</th>
                                <th width="70px">Edit</th>
                                @can('delete ability')
                                    <th width="70px">Delete</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>
                                        {{ $service['label'] }}
                                    </td>
                                    <td>
                                        {{ $service['type'] == 'one-time' ? 'One Time' : 'Recurring' }}
                                    </td>
                                    <td>
                                        ${{ $service['price'] }}{{ $service['type'] == 'one-time' ? '' : '/monthly' }}
                                    </td>
                                    <td>
                                        {!! $service['content'] !!}
                                    </td>
                                    <td>
                                        <a href = "{{ url('edit-service/' . $service['id']) }}">
                                            <button type="button" class="btn btn-info">Edit</button>
                                        </a>
                                    </td>
                                    @can('delete ability')
                                        <td>
                                            <a href = "{{ url('delete-service/' . $service['id']) }}">
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </a>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
