@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')

    @include('partials.form-errors')

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header with-border">
                    <h3 class="card-title">
                        Add Admin
                    </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>

                            <div>
                                <input type="text" name = "name" class="form-control" id="name" placeholder="Full Name" value="{{ old('name', '') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>

                            <div>
                                <input type="email" name = "email" class="form-control" placeholder="Email" value="{{ old('email', '') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>

                            <div>
                                <input id = "password" type="password" name = "password" class="form-control" placeholder="Password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Confirm</label>

                            <div>
                                <input id = "password-confirmation" type="password" name = "password_confirmation" class="form-control" placeholder="Confirm password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Permissions</label>

                            <div>
                                <select id = "permission-select" class="form-control" name="permissions[]" multiple="multiple" data-placeholder="Select permissions">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}" {{ in_array($permission->name, old("permissions", [])) ? 'selected' : '' }}>
                                            {{ ucfirst($permission->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Employee Status</label>
                            <div class="">
                                <select class="form-control" name="type" style="width: 100%;">
                                    @foreach (\App\User::types() as $key => $value)
                                        <option value="{{ $key }}" {{ old_selected('type', $key) }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Job Roles</label>
                            <div>
                                <input type="text" name = "job_roles" class="form-control" value="{{ old('job_roles') }}" />
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info pull-right">Confirm</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var userId = -1;
    </script>
    <script>
        $('#permission-select').select2()
    </script>
@endsection
