@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-shield-alt me-2"></i>Permissions</h4>
        <div class="small text-muted">Manage access permissions</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary"><i class="fas fa-user-shield me-1"></i> Manage Roles</a>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New Permission</a>
    </div>
    </div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('permissions.index') }}" class="row g-2 mb-3">
            <div class="col-md-6">
                <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search permission name">
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
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td><span class="badge bg-secondary">{{ $permission->guard_name }}</span></td>
                            <td>{{ $permission->created_at?->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this permission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">No permissions found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $permissions->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection



