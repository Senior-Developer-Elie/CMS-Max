@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-industry"></i> Industries : {{ count($blogIndustries) }}</h3>

                    <div class="card-tools pull-right">
                        <button id="add-blog-industry-button" type="button" class="btn btn-default pull-right">
                            <i class="fa fa-plus"></i> Add Industry
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Assigned Websites</th>
                                <th class="text-center" width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $blogIndustries as $blogIndustry )
                                <tr data-blog-industry-id="{{ $blogIndustry->id }}" data-blog-industry-name="{{ $blogIndustry->name }}">
                                    <td>
                                        {{ $blogIndustry->name }}
                                    </td>
                                    <td>
                                        <a href="{{ url('/blog-industry-client-list?blogIndustryId=' . $blogIndustry->id ) }}" style="width:100%; display:block;">
                                            {{ count($blogIndustry->websites) }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-edit edit-blog-industry-button" data-toggle="tooltip" data-placement="top" title="Edit Industry"></i>
                                        @can('delete ability')
                                            <i class="fa fa-trash-o delete-blog-industry-button" data-toggle="tooltip" data-placement="top" title="Delete Industry"></i>
                                        @endcan
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
