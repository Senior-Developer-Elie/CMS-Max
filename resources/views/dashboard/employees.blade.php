<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Employees ({{ count($employees) }})</h3>
    </div>
    <div class="card-body">
        <table id="users-table" class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Client Lead</th>
                    <th>Project Manager</th>
                    <th>Job Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.edit', $user) }}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            @if ($user->clientLeads->where('archived', 0)->count() > 0)
                                <a href="{{ route('clients.index', ['user_id' => $user->id, 'filter_type' => 'client_lead']) }}">
                                    {{ $user->clientLeads->count() }}
                                </a>
                            @else
                                {{ $user->clientLeads->where('archived', 0)->count() }}
                            @endif
                        </td>
                        <td>
                            @if ($user->projectManagers->count() > 0)
                                <a href="{{ route('clients.index', ['user_id' => $user->id, 'filter_type' => 'project_manager']) }}">
                                    {{ $user->projectManagers->count() }}
                                </a>
                            @else
                                {{ $user->projectManagers->count() }}
                            @endif
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