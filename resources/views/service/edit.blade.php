@extends('layouts.theme')
@section('content-header')
    <h1>
        Edit Service
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-tools">
                        <a href = "{{ url('manage-default-text') }}">
                            <button type="button" class="btn btn-info mb-1"><i class = "fa fa-arrow-left"></i></button>
                        </a>
                    </div>
                </div>
                <form role = "form" method="POST" action="{{ isset($service) ? url('edit-service/' . $service->id) : url('/add-service') }}">
                    @csrf
                    <input type="hidden" class="form-control" name="name" value="{{ isset($service) ? $service->name : uniqid() }}">
                    <div class="card-body">
                        <div class = "row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="label" value="{{ isset($service) ? $service->label : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="type">
                                        <option {{ (isset($service) && $service->type == 'one-time') ? 'selected' : '' }} value = "one-time">One Time</option>
                                        <option {{ (isset($service) && $service->type == 'recurring') ? 'selected' : '' }} value = "recurring">Recuring</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <input type="text" class="form-control" id="price" name = "price" placeholder="Enter price" value="{{ isset($service) ? $service->price : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea id = "service-content" class="form-control" name="content">{!! isset($service) ? $service->content : '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ isset($service) ? 'Update Service' : 'Add Service' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
        .create( document.querySelector('#service-content'), {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
    </script>
@endsection
