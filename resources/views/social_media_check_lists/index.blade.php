@extends('layouts.theme')

@section('content-header')
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="m-0">Social Media Check Lists</h3>
        <a href="{{ route('social_media_check_lists.create') }}">
            <button type="button" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> Add Check List
            </button>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @foreach (\App\SocialMediaCheckList::checkListTypes() as $checkListKey => $checkListName)
                @php
                    $socialMediaCheckLists = \App\SocialMediaCheckList::byTarget($checkListKey)->get();
                @endphp
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $checkListName }}</h3>
                    </div>
                    <div class="card-body">
                        <ul class="todo-list ui-sortable" data-widget="todo-list">
                            @foreach ($socialMediaCheckLists as $socialMediaCheckList)
                                <li>
                                    {{-- <span class="handle ui-sortable-handle">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <i class="fas fa-ellipsis-v"></i>
                                    </span> --}}

                                    <span class="text">{{ $socialMediaCheckList->text }}</span>

                                    <div class="tools">
                                        <a href="{{ route('social_media_check_lists.edit', $socialMediaCheckList) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection