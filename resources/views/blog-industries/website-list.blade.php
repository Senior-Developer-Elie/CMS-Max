@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-users"></i>

                    <h3 class="card-title">Websites assigned to business <strong>{{ $blogIndustry->name }}</strong></h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Website Name</th>
                                <th>Website</th>
                                <th>Target Area</th>
                                <th>Frequency</th>
                                <th>Writer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $blogIndustry->websites as $website )
                                <tr>
                                    <td>
                                        <a href="{{ url('client-history?clientId=' . $website->client()->id) }}">
                                            {{ $website->client()->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $website->name }}
                                    </td>
                                    <td>
                                        <a href = "//{{ $website->website }}" target="_blank">
                                            {{ $website->website }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $website->target_area }}
                                    </td>
                                    <td style="text-transform:capitalize">
                                        {{ $website->frequency }}
                                    </td>
                                    <td>
                                        {{ is_null($website->assignee()) ? '' : $website->assignee()->name }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--modals-->
    @include('blog-industries.modals.add')
    @include('blog-industries.modals.delete')

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/blog-industries.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/blog-industries.js') }}"></script>
@endsection
