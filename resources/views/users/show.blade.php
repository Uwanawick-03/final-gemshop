@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-user me-2"></i>{{ $user->name }}</h4>
        <div class="small text-muted">{{ $user->email }}</div>
    </div>
    <div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit me-1"></i> Edit</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-3">Status</h6>
                    <div class="mb-2">
                        <span class="text-muted">Role</span>
                        <div><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></div>
                    </div>
                    <div>
                        <span class="text-muted">Active</span>
                        <div>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-3">Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="small text-muted">Name</div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Email</div>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Phone</div>
                            <div class="fw-semibold">{{ $user->phone ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Member Since</div>
                            <div class="fw-semibold">{{ $user->created_at?->format('Y-m-d') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


