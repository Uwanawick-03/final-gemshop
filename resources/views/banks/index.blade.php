@extends('layouts.app')

@section('title', 'Banks Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-university me-2"></i>Banks Management</h2>
        <p class="text-muted mb-0">Manage bank profiles and accounts</p>
    </div>
    <a href="{{ route('banks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Bank</a>
</div>

<div class="card mb-4"><div class="card-body">
    <form method="GET" action="{{ route('banks.index') }}" class="row g-3">
        <div class="col-md-6"><label class="form-label">Search</label><input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name, branch, swift, or account number..."></div>
        <div class="col-md-3"><label class="form-label">Currency</label><select class="form-select" name="currency"><option value="">All</option>@foreach($currencies as $cur)<option value="{{ $cur }}" {{ request('currency') == $cur ? 'selected' : '' }}>{{ $cur }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Active</label><select class="form-select" name="active"><option value="">All</option><option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Yes</option><option value="0" {{ request('active') == '0' ? 'selected' : '' }}>No</option></select></div>
        <div class="col-md-1"><label class="form-label">&nbsp;</label><button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search"></i></button></div>
    </form>
</div></div>

<div class="card"><div class="card-body">
    @if($banks->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Code</th><th>Name</th><th>Account</th><th>SWIFT</th><th>Currency</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($banks as $bank)
                <tr>
                    <td><strong class="text-primary">{{ $bank->bank_code }}</strong></td>
                    <td><strong>{{ $bank->display_name }}</strong>@if($bank->email)<br><small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $bank->email }}</small>@endif @if($bank->phone)<br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $bank->phone }}</small>@endif</td>
                    <td>@if($bank->account_number)<strong>{{ $bank->account_number }}</strong><br><small class="text-muted">{{ $bank->account_name }}</small>@else<span class="text-muted">-</span>@endif</td>
                    <td>{{ $bank->swift_code ?? '-' }}</td>
                    <td>{{ $bank->currency ?? '-' }}</td>
                    <td>@if($bank->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('banks.show', $bank) }}" class="btn btn-sm btn-outline-info" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('banks.edit', $bank) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('banks.destroy', $bank) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bank?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">{{ $banks->withQueryString()->links() }}</div>
    @else
    <div class="text-center py-5"><i class="fas fa-university fa-3x text-muted mb-3"></i><h5 class="text-muted">No banks found</h5><p class="text-muted">Start by adding your first bank to the system.</p><a href="{{ route('banks.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Bank</a></div>
    @endif
</div></div>
@endsection
