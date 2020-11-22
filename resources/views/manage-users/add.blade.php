@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header with-border">
                    <h3 class="card-title">
                        {{ isset($user) ? 'Edit Admin' : 'Add Admin' }}
                    </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group @error('name') has-error @enderror">
                            <label for="name" class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10">
                                <input type="text" name = "name" class="form-control" id="name" placeholder="Full Name" value="{{ isset($user) ? $user['name'] : old('name') }}" autocomplete="name" autofocus>
                                @error('name')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group @error('email') has-error @enderror">
                            <label for="email" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <input type="email" name = "email" class="form-control" id="email" placeholder="Email" value="{{ isset($user) ? $user['email'] : old('email') }}" autocomplete="email">
                                @error('email')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group @error('password') has-error @enderror">
                            <label for="password" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input id = "password" type="password" name = "password" class="form-control" id="password" placeholder="Password" value="{{ old('password') }}" autocomplete="new-password">
                                @error('password')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Confirm</label>

                            <div class="col-sm-10">
                                <input id = "password-confirmation" type="password" name = "password_confirmation" class="form-control" id="password" placeholder="Confirm password" value="{{ old('password_confirmation') }}" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Permissions</label>

                            <div class="col-sm-10">
                                <select id = "permission-select" class="form-control" name="permissions[]" multiple="multiple" data-placeholder="Select permissions">
                                    <?php
                                        if( isset($user) )
                                            $oldPermissions = $user['permissions'];
                                        else
                                            $oldPermissions = is_null(old("permissions")) ? [] : old("permissions");
                                    ?>
                                    @foreach ($allPermissions as $permission)
                                        <option value="{{ $permission }}" {{ in_array($permission, $oldPermissions) ? 'selected' : '' }}>{{ $permission }}</option>
                                    @endforeach
                                </select>
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

        @if( isset($user) )
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
                        @foreach ($allPagePermissions as $pagePermission)
                            <div class="page-item-container" style="position: relative; top: 0px; left: 0px;">
                                <div class="page-item {{ empty($pagePermission['subPages']) ? '' : 'parent-page-item' }}
                                    {{ $objectUser->hasPagePermission($pagePermission['name']) ? 'selected' : '' }}"
                                    data-page-name = "{{ $pagePermission['name'] }}">
                                    <i class="{{ $pagePermission['icon'] }} page-item-icon"></i>
                                    <p class="page-item-txt" contenteditable="false"> {{ isset($pagePermission['title']) ? $pagePermission['title'] : $pagePermission['name'] }}</p>
                                </div>
                                @if( !empty($pagePermission['subPages']) )
                                    <div class="sub-page-items-wrapper">
                                        @foreach ($pagePermission['subPages'] as $subPagePermission)
                                            <div class="page-item-container" style="position: relative; top: 0px; left: 0px;">
                                                <div class="page-item {{ $objectUser->hasPagePermission($subPagePermission['name']) ? 'selected' : '' }}"
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
        @endif
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/edit-user-page-permission.css') }}">
@endsection

@section('javascript')
    <script>
        var userId = {{ isset($user) ? $objectUser->id : -1 }};
    </script>
    <script>
        $('#permission-select').select2()
    </script>
    <script src="{{ asset('assets/js/edit-user-page-permission.js') }}"></script>
@endsection
