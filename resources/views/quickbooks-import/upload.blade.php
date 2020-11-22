@extends('layouts.theme')

@section('content-header')
    <h1>
        QuickBooks Import
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-4">
            <form role = "form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">CSV File</label>

                            <div class="col-sm-10">
                                <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pull-right">Show</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
