@extends('layouts.theme')

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-5 col-12">
            <div class="card card-primary card-outline">
                <form id = "upload-form" class="form-style-7" method = "POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h2 class="card-title">PDF to JPEG Converter</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="file">PDF File</label>
                            <input type="file" name="file" accept="application/pdf">
                        </div>
                        <div class="form-group">
                            <label for="width">Width <small>Input image width in px</small></label>
                            <input id = "width" type="text" class="form-control" name="width" placeholder="" value = "1100">
                            <small class="form-text text-muted">Input image width in px.</small>
                        </div>
                        <div class="form-group">
                            <label for="space">Space</label>
                            <input id = "space" type="text" class="form-control" name="space" placeholder="" value = "20">
                            <small class="form-text text-muted">Input space between pages in px.</small>
                        </div>
                        <div class="form-group">
                            <label for="quality">Quality</label>
                            <input id = "quality" type="text" class="form-control" name="quality" placeholder="" value = "80">
                            <small class="form-text text-muted">Input quality of your image.</small>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rotate-pages" name="rotate">
                            <label class="form-check-label" for="rotate-pages">Rotate Pages</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class = "d-flex justify-content-center">
                            <button  id = "convert-button"  type="button" class="btn btn-primary">Convert</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-sub-title{
            font-size: 13px;
            padding: 0 30px;
            color: #555;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('assets/js/pdf2convert.js') }}"></script>
@stop
