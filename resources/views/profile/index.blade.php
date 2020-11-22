@extends('layouts.theme')

@section('content-header')
    <h1>
        User Profile
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="avatar-wrapper">
                        <img class="profile-user-img img-responsive img-circle" src="{{ Auth::user()->getPublicAvatarLink() }}"
                            alt="User profile picture">
                        <div class="middle">
                            <div class="text">
                                <i class="fa fa-edit">
                                </i>
                            </div>
                        </div>
                    </div>

                    <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                    <p class="text-muted text-center">{{ Auth::user()->job_title() }}</p>
                    <hr>
                    <strong><i class="fa fa-pencil margin-r-5"></i> Permissions</strong>
                    <p>
                        @foreach( array_column(Auth::user()->getAllPermissions()->toArray(), 'name') as $permission )
                        <span class="label label-primary" style="text-transform:capitalize">{{ $permission }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Settings</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ Auth::user()->name }}" required>
                                @error('name')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ Auth::user()->email }}" required>
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
                            <div class="col-10">
                                <button type="submit" class="btn btn-danger pull-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('profile.modals.photo-upload')

    <!--Hidden Input File For selecting Avatar -->
    <input type="file" accept="image/*" id="photo-file" style="display:none">
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/lib/Croppie/croppie.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/lib/Croppie/croppie.js') }}"></script>
    <script src="{{ asset('assets/js/profile.js') }}">
    </script>
@endsection
