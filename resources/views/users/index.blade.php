@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admins List</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.create') }}">
                            <button type="button" class="btn btn-primary mb-1"><i class = "fa fa-plus"></i></button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="proposal-list" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50px"></th>
                                <th width="115px">Name</th>
                                <th>Email</th>
                                <th>Employee Status</th>
                                <th>Permissions</th>
                                <th width = "75px">Edit</th>
                                @can('delete ability')
                                    <th width = "75px">Delete</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <img class="img-responsive img-circle profile-image" src="{{ $user->getPublicAvatarLink() }}">
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        {{ ucfirst($user->type) }}
                                    </td>
                                    <td style="text-transform: capitalize;">
                                        @if ($user->hasRole('super admin'))
                                            All
                                        @else
                                            {{ implode(', ', $user->permission_names) }}
                                        @endif
                                    </td>
                                    @if (! $user->hasRole('super admin'))
                                        <td>
                                            <a href="{{ route('users.edit', $user) }}">
                                                <button type="button" class="btn btn-primary">Edit</button>
                                            </a>
                                        </td>
                                        @can('delete ability')
                                            <td>
                                                <a href="{{ route('users.confirm-delete', $user) }}">
                                                    <button type="button" class="btn btn-danger">Delete</button>
                                                </a>
                                            </td>
                                        @endcan
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Admin Permissions</h3>

                        <div class="card-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="150px">Name</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $permissions as $permission )
                                    <tr class="permission-row" data-permission-id="{{ $permission->id }}">
                                        <td style="text-transform:capitalize;">
                                            <a href = "#" class="name">
                                                {{ $permission->name }}
                                            </a>
                                        </td>
                                        <td class="description">
                                            <?php echo $permission->description; ?>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    @include('users.modals.edit-description-modal')
@endsection

@section('css')
<style>
    td{
        vertical-align: middle !important;
    }
    .profile-image{
        width: 50px;
    }
    .permission-row .description p{
        margin: 0 !important;
    }
</style>
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/lib/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/manage-user.js') }}"></script>
@endsection
