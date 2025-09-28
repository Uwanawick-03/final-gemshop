@extends('layouts.app')

@section('title', 'Bank Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-university me-2"></i>{{ $bank->display_name }}</h4>
        <div class="small text-muted">{{ $bank->bank_code }} • {{ $bank->currency ?? '—' }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('banks.edit', $bank) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Edit</a>
        <a href="{{ route('banks.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Account Information</h5></div><div class="card-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label fw-bold">Account Number</label><p class="mb-0">{{ $bank->account_number ?? '—' }}</p></div>
                <div class="col-12"><label class="form-label fw-bold">Account Name</label><p class="mb-0">{{ $bank->account_name ?? '—' }}</p></div>
                <div class="col-md-6"><label class="form-label fw-bold">SWIFT</label><p class="mb-0">{{ $bank->swift_code ?? '—' }}</p></div>
                <div class="col-md-6"><label class="form-label fw-bold">Currency</label><p class="mb-0">{{ $bank->currency ?? '—' }}</p></div>
            </div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Contact</h5></div><div class="card-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label fw-bold">Email</label><p class="mb-0">@if($bank->email)<a href="mailto:{{ $bank->email }}">{{ $bank->email }}</a>@else — @endif</p></div>
                <div class="col-12"><label class="form-label fw-bold">Phone</label><p class="mb-0">{{ $bank->phone ?? '—' }}</p></div>
                <div class="col-12"><label class="form-label fw-bold">Address</label><p class="mb-0">@if($bank->address){{ $bank->address }}@else — @endif @if($bank->city), {{ $bank->city }}@endif @if($bank->country), {{ $bank->country }}@endif</p></div>
                <div class="col-12"><label class="form-label fw-bold">Status</label><p class="mb-0">@if($bank->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif</p></div>
            </div>
        </div></div>
    </div>
</div>

@if($bank->notes)
<div class="row"><div class="col-12"><div class="card mb-4"><div class="card-header"><h5 class="mb-0">Notes</h5></div><div class="card-body"><p class="mb-0">{{ $bank->notes }}</p></div></div></div></div>
@endif

<div class="row mt-4"><div class="col-12"><div class="card"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Quick Actions</h6><small class="text-muted">Manage this bank</small></div><div class="d-flex gap-2"><a href="{{ route('banks.edit', $bank) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Edit</a><form action="{{ route('banks.destroy', $bank) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bank?')">@csrf @method('DELETE')<button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Delete</button></form></div></div></div></div></div>
@endsection
