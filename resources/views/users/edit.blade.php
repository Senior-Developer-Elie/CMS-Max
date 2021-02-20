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
                        Edit Admin
                    </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route("users.update", $user) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>

                            <div>
                                <input type="text" name = "name" class="form-control" id="name" placeholder="Full Name" value="{{ old('name', $user->name) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>

                            <div>
                                <input type="email" name = "email" class="form-control" placeholder="Email" value="{{ old('email', $user->email) }}">
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
                                        <option value="{{ $permission->name }}" {{ in_array($permission->name, old("permissions", $user->permission_names)) ? 'selected' : '' }}>
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
                                        <option value="{{ $key }}" {{ old_selected('type', $key, $user->type) }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Job Roles</label>
                            <div>
                                <input type="text" name = "job_roles" class="form-control" value="{{ old('job_roles', $user->job_roles) }}" />
                            </div>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="email_notification_enabled" {{ old_checked('email_notification_enabled', $user->email_notification_enabled) }}>
                                <strong class="ml-1">Enable Email Notification</strong>
                            </label>
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

        <div class="col-md-6 col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Page Permissions</strong></h3>
                    <div class="card-tools pull-right">
                        <button type="button"
                                class="btn btn-sm"
                                data-card-widget="collapse"
                                data-toggle="tooltip"
                                title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($pagePermissions as $pagePermission)
                        <div class="page-item-container" style="position: relative; top: 0px; left: 0px;">
                            <div class="page-item {{ empty($pagePermission['subPages']) ? '' : 'parent-page-item' }}
                                {{ $user->hasPagePermission($pagePermission['name']) ? 'selected' : '' }}"
                                data-page-name = "{{ $pagePermission['name'] }}">
                                <i class="{{ $pagePermission['icon'] }} page-item-icon"></i>
                                <p class="page-item-txt" contenteditable="false"> {{ isset($pagePermission['title']) ? $pagePermission['title'] : $pagePermission['name'] }}</p>
                            </div>
                            @if( !empty($pagePermission['subPages']) )
                                <div class="sub-page-items-wrapper">
                                    @foreach ($pagePermission['subPages'] as $subPagePermission)
                                        <div class="page-item-container" style="position: relative; top: 0px; left: 0px;">
                                            <div class="page-item {{ $user->hasPagePermission($subPagePermission['name']) ? 'selected' : '' }}"
                                                data-page-name = "{{ $subPagePermission['name'] }}">
                                                <i class="{{ $subPagePermission['icon'] }} page-item-icon"></i>
                                                <p class="page-item-txt" contenteditable="false"> {{ isset($subPagePermission['title']) ? $subPagePermission['title'] : $subPagePermission['name'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button id ="page-permission-update-btn" type="button" class="btn btn-info pull-right">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/edit-user-page-permission.css') }}">
@endsection

@section('javascript')
    <script>
        var userId = {{ $user->id }};
    </script>
    <script>
        $('#permission-select').select2()
    </script>
    <script src="{{ asset('assets/js/edit-user-page-permission.js') }}"></script>
@endsection
