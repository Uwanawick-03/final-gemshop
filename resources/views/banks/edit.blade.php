@extends('layouts.app')

@section('title', 'Edit Bank')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-university me-2"></i>Edit Bank</h4>
        <div class="small text-muted">Update {{ $bank->display_name }}</div>
    </div>
    <a href="{{ route('banks.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card"><div class="card-body">
<form method="POST" action="{{ route('banks.update', $bank) }}" class="row g-3">
@csrf
@method('PUT')

<div class="col-md-6"><label class="form-label">Bank Name <span class="text-danger">*</span></label><input name="name" value="{{ old('name', $bank->name) }}" class="form-control @error('name') is-invalid @enderror" required></div>
<div class="col-md-6"><label class="form-label">Branch</label><input name="branch" value="{{ old('branch', $bank->branch) }}" class="form-control @error('branch') is-invalid @enderror"></div>
<div class="col-md-4"><label class="form-label">SWIFT Code</label><input name="swift_code" value="{{ old('swift_code', $bank->swift_code) }}" class="form-control @error('swift_code') is-invalid @enderror"></div>
<div class="col-md-4"><label class="form-label">Currency</label><input name="currency" value="{{ old('currency', $bank->currency) }}" class="form-control @error('currency') is-invalid @enderror" placeholder="e.g. USD"></div>
<div class="col-md-4"><label class="form-label">Active</label><div class="form-check mt-2"><input name="is_active" type="checkbox" class="form-check-input" {{ old('is_active', $bank->is_active) ? 'checked' : '' }}><label class="form-check-label">Active in system</label></div></div>

<div class="col-md-6"><label class="form-label">Account Number</label><input name="account_number" value="{{ old('account_number', $bank->account_number) }}" class="form-control @error('account_number') is-invalid @enderror"></div>
<div class="col-md-6"><label class="form-label">Account Name</label><input name="account_name" value="{{ old('account_name', $bank->account_name) }}" class="form-control @error('account_name') is-invalid @enderror"></div>

<div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" value="{{ old('email', $bank->email) }}" class="form-control @error('email') is-invalid @enderror"></div>
<div class="col-md-6"><label class="form-label">Phone</label><input name="phone" value="{{ old('phone', $bank->phone) }}" class="form-control @error('phone') is-invalid @enderror"></div>

<div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $bank->address) }}</textarea></div>
<div class="col-md-6"><label class="form-label">City</label><input name="city" value="{{ old('city', $bank->city) }}" class="form-control @error('city') is-invalid @enderror"></div>
<div class="col-md-6"><label class="form-label">Country</label><input name="country" value="{{ old('country', $bank->country) }}" class="form-control @error('country') is-invalid @enderror"></div>

<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $bank->notes) }}</textarea></div>

<div class="col-12"><hr><div class="d-flex justify-content-end gap-2"><a href="{{ route('banks.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Bank</button></div></div>
</form>
</div></div>
@endsection
