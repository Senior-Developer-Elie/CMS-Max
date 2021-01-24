<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Contractors</h3>
    </div>
    <div class="card-body">
        <table id="users-table" class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Job Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contractors as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.edit', $user) }}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            {{ $user->job_roles }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>