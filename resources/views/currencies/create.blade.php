@extends('layouts.app')

@section('title', 'Add New Currency')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Add New Currency</h2>
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
                <form action="{{ route('currencies.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="e.g., USD, EUR, GBP" maxlength="3" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">3-letter currency code (ISO 4217)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Currency Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., US Dollar, Euro, British Pound" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('symbol') is-invalid @enderror" 
                                   id="symbol" name="symbol" value="{{ old('symbol') }}" 
                                   placeholder="e.g., $, €, £" maxlength="10" required>
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="exchange_rate" class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('exchange_rate') is-invalid @enderror" 
                                   id="exchange_rate" name="exchange_rate" value="{{ old('exchange_rate') }}" 
                                   step="0.0001" min="0" required>
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Rate relative to base currency (LKR)</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_base_currency" 
                                       name="is_base_currency" value="1" {{ old('is_base_currency') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_base_currency">
                                    Set as Base Currency
                                </label>
                            </div>
                            <div class="form-text">Only one currency can be the base currency</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                            <div class="form-text">Active currencies can be used in transactions</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('currencies.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Currency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Currency Guidelines</h5>
            </div>
            <div class="card-body">
                <h6>Currency Code</h6>
                <p class="small text-muted">Use 3-letter ISO 4217 codes like USD, EUR, GBP, etc.</p>
                
                <h6>Exchange Rate</h6>
                <p class="small text-muted">Enter the rate relative to your base currency (LKR). For example, if 1 USD = 320 LKR, enter 0.0031.</p>
                
                <h6>Base Currency</h6>
                <p class="small text-muted">Only one currency can be the base currency. Setting a new base currency will automatically unset the previous one.</p>
                
                <h6>Common Exchange Rates</h6>
                <ul class="small text-muted">
                    <li>USD: 0.0031 (1 USD = ~320 LKR)</li>
                    <li>EUR: 0.0028 (1 EUR = ~360 LKR)</li>
                    <li>GBP: 0.0025 (1 GBP = ~400 LKR)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
