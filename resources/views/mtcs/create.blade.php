@extends('layouts.app')

@section('title', 'Create MTC')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>Create MTC
            </h1>
        </div>
        <a href="{{ route('mtcs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to MTCs
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('mtcs.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="item_id" class="form-label">Item *</label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="item_id" name="item_id" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} - {{ $item->item_code }}
                            </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="customer_id" class="form-label">Customer *</label>
                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }} - {{ $customer->customer_code }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sales_assistant_id" class="form-label">Sales Assistant *</label>
                        <select class="form-select @error('sales_assistant_id') is-invalid @enderror" id="sales_assistant_id" name="sales_assistant_id" required>
                            <option value="">Select Sales Assistant</option>
                            @foreach($salesAssistants as $assistant)
                            <option value="{{ $assistant->id }}" {{ old('sales_assistant_id') == $assistant->id ? 'selected' : '' }}>
                                {{ $assistant->full_name }} - {{ $assistant->employee_code }}
                            </option>
                            @endforeach
                        </select>
                        @error('sales_assistant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="issue_date" class="form-label">Issue Date *</label>
                        <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                               id="issue_date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required>
                        @error('issue_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date *</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                               id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price *</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('purchase_price') is-invalid @enderror" 
                               id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                        @error('purchase_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="selling_price" class="form-label">Selling Price *</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('selling_price') is-invalid @enderror" 
                               id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('mtcs.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create MTC
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
