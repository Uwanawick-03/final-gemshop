@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-truck me-2"></i>Edit Supplier</h4>
        <div class="small text-muted">Update supplier profile</div>
    </div>
    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-3">
                <label class="form-label">Supplier Code</label>
                <input name="supplier_code" value="{{ old('supplier_code', $supplier->supplier_code) }}" class="form-control @error('supplier_code') is-invalid @enderror">
                @error('supplier_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5">
                <label class="form-label">Company Name</label>
                <input name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="form-control @error('company_name') is-invalid @enderror">
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact Person</label>
                <input name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="form-control @error('contact_person') is-invalid @enderror">
                @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input name="email" type="email" value="{{ old('email', $supplier->email) }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input name="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Payment Terms</label>
                <select name="payment_terms" class="form-select @error('payment_terms') is-invalid @enderror">
                    @foreach($terms as $t)
                        <option value="{{ $t }}" @selected(old('payment_terms', $supplier->payment_terms)===$t)>{{ strtoupper(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
                @error('payment_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $supplier->address) }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">City</label>
                <input name="city" value="{{ old('city', $supplier->city) }}" class="form-control @error('city') is-invalid @enderror">
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Country</label>
                <input name="country" value="{{ old('country', $supplier->country) }}" class="form-control @error('country') is-invalid @enderror">
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Tax ID</label>
                <input name="tax_id" value="{{ old('tax_id', $supplier->tax_id) }}" class="form-control @error('tax_id') is-invalid @enderror">
                @error('tax_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Credit Limit</label>
                <input name="credit_limit" type="number" step="0.01" value="{{ old('credit_limit', $supplier->credit_limit) }}" class="form-control @error('credit_limit') is-invalid @enderror">
                @error('credit_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Current Balance</label>
                <input name="current_balance" type="number" step="0.01" value="{{ old('current_balance', $supplier->current_balance) }}" class="form-control @error('current_balance') is-invalid @enderror">
                @error('current_balance')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $supplier->is_active))>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $supplier->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection










