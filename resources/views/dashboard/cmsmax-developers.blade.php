<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">CMS Max Developer ({{ count($users) }})</h3>
    </div>
    <div class="card-body">
        <table id="users-table" class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Employee Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.edit', $user) }}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            {{ ucfirst($user->type) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>