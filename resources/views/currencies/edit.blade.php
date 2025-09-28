@extends('layouts.app')

@section('title', 'Edit Currency')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Currency</h2>
    <a href="{{ route('currencies.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Currencies
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Currency Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('currencies.update', $currency) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $currency->code) }}" 
                                   placeholder="e.g., USD, EUR, GBP" maxlength="3" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">3-letter currency code (ISO 4217)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Currency Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $currency->name) }}" 
                                   placeholder="e.g., US Dollar, Euro, British Pound" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('symbol') is-invalid @enderror" 
                                   id="symbol" name="symbol" value="{{ old('symbol', $currency->symbol) }}" 
                                   placeholder="e.g., $, €, £" maxlength="10" required>
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="exchange_rate" class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('exchange_rate') is-invalid @enderror" 
                                   id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate) }}" 
                                   step="0.0001" min="0" required>
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Rate relative to base currency (LKR)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_base_currency" 
                                       name="is_base_currency" value="1" {{ old('is_base_currency', $currency->is_base_currency) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_base_currency">
                                    Set as Base Currency
                                </label>
                            </div>
                            <div class="form-text">Only one currency can be the base currency</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $currency->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                            <div class="form-text">Active currencies can be used in transactions</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('currencies.show', $currency) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Currency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Currency Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <p class="mb-0">{{ $currency->created_at->format('M d, Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p class="mb-0">{{ $currency->updated_at->format('M d, Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <div>
                        @if($currency->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                        
                        @if($currency->is_base_currency)
                            <span class="badge bg-primary ms-2">Base Currency</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('currencies.show', $currency) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a>
                    <a href="{{ route('currencies.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>All Currencies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
