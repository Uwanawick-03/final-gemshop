@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-user-shield me-2"></i>Roles</h4>
        <div class="small text-muted">Manage roles and their access</div>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New Role</a>
    </div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('roles.index') }}" class="row g-2 mb-3">
            <div class="col-md-6">
                <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search role name">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Guard</th>
                        <th>Permissions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td><span class="badge bg-secondary">{{ $role->guard_name }}</span></td>
                            <td>
                                <span class="text-muted">{{ $role->permissions()->count() }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">No roles found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $roles->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection


