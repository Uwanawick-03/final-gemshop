@extends('layouts.app')

@section('title', 'Currency Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-coins me-2"></i>Currency Details</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('currencies.edit', $currency) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <a href="{{ route('currencies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Currency Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Currency Code</label>
                        <p class="mb-0">
                            <span class="badge bg-primary fs-5">{{ $currency->code }}</span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Currency Name</label>
                        <p class="mb-0 fs-5">{{ $currency->name }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Currency Symbol</label>
                        <p class="mb-0 fs-4">{{ $currency->symbol }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Exchange Rate</label>
                        <p class="mb-0 fs-5">{{ number_format($currency->exchange_rate, 4) }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Base Currency</label>
                        <p class="mb-0">
                            @if($currency->is_base_currency)
                                <span class="badge bg-primary fs-6">Yes - Base Currency</span>
                            @else
                                <span class="text-muted">No</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Status</label>
                        <p class="mb-0">
                            @if($currency->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-secondary fs-6">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Exchange Rate Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Exchange Rate Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted">Rate to Base Currency (LKR)</h6>
                            <h4 class="text-primary">{{ number_format($currency->exchange_rate, 4) }}</h4>
                            <small class="text-muted">1 {{ $currency->code }} = {{ number_format($currency->exchange_rate, 4) }} LKR</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted">Rate from Base Currency (LKR)</h6>
                            <h4 class="text-success">{{ number_format(1 / $currency->exchange_rate, 4) }}</h4>
                            <small class="text-muted">1 LKR = {{ number_format(1 / $currency->exchange_rate, 4) }} {{ $currency->code }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Currency Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Created</span>
                    <span class="fw-bold">{{ $currency->created_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Last Updated</span>
                    <span class="fw-bold">{{ $currency->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Days Active</span>
                    <span class="fw-bold">{{ $currency->created_at->diffInDays(now()) }}</span>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('currencies.edit', $currency) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Currency
                    </a>
                    <a href="{{ route('currencies.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>All Currencies
                    </a>
                    @if(!$currency->is_base_currency)
                        <form action="{{ route('currencies.destroy', $currency) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this currency?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Currency
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Currency Preview -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Currency Preview</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <span class="display-6">{{ $currency->symbol }}</span>
                </div>
                <h5>{{ $currency->code }}</h5>
                <p class="text-muted">{{ $currency->name }}</p>
                <div class="small text-muted">
                    Sample: {{ $currency->symbol }}1,000.00
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
