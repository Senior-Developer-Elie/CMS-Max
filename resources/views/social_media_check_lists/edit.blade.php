@extends('layouts.theme')

@section('content-header')
    <h3>Edit Check List</h3>
@endsection

@section('content')
    @include('partials.form-errors')

    <form role="form" action="{{ route("social_media_check_lists.update", $socialMediaCheckList) }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        <div class="row">
            <div class="col-lg-9">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <i class="fa fa-calendar-times-o"></i>
    
                        <h3 class="card-title">Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Target</label>
                            <select class="form-control" name="target" style="width: 100%;">
                                <option value=""></option>
                                @foreach (\App\SocialMediaCheckList::checkListTypes() as $key => $name)
                                    <option value="{{ $key }}" {{ old_selected('target', $key, $socialMediaCheckList->target) }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Text</label>
                            <input type="text" class="form-control" name="text" value="{{ old('text', $socialMediaCheckList->text) }}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Save</h3>
                    </div>
                    <div class="card-body clearfix">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection