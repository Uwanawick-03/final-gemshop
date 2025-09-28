@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-truck me-2"></i>{{ $supplier->display_name }}</h4>
        <div class="small text-muted">{{ $supplier->full_address }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> Edit</a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
    </div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Company</div>
                    <div class="col-sm-8">{{ $supplier->company_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Contact Person</div>
                    <div class="col-sm-8">{{ $supplier->contact_person }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Email</div>
                    <div class="col-sm-8">{{ $supplier->email ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Phone</div>
                    <div class="col-sm-8">{{ $supplier->phone }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Address</div>
                    <div class="col-sm-8">{{ $supplier->full_address }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Tax ID</div>
                    <div class="col-sm-8">{{ $supplier->tax_id ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Payment Terms</div>
                    <div class="col-sm-8"><span class="badge bg-secondary">{{ strtoupper(str_replace('_',' ',$supplier->payment_terms)) }}</span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Status</div>
                    <div class="col-sm-8">
                        @if($supplier->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Notes</div>
                    <div class="col-sm-8">{{ $supplier->notes ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-muted">Credit Limit</div>
                    <div>{{ number_format((float)$supplier->credit_limit, 2) }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-muted">Current Balance</div>
                    <div>{{ number_format((float)$supplier->current_balance, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection










