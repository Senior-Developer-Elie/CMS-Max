@extends('layouts.theme')

@section('content-header')
    <h3>Clients List</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="client-list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                Client Name
                            </tr>
                            <tr>
                                Website
                            </tr>
                            <tr>
                                Updated At
                            </tr>
                            <tr>
                                Status
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($websites as $website)
                            <tr>
                                <td>
                                    {{ $website['client']->name }}
                                </td>
                                <td>
                                    {{ $website['website'] }}
                                </td>
                                <td>
                                    @if($website['isExistOnServer'])
                                        <span class="label bg-green">
                                            Existing on server
                                        </span>
                                    @else
                                        <span class="label bg-red">
                                            Not Exsting on server
                                        </span>
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
@endsection
