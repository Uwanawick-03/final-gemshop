@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('finished-good-transfers.index') }}">Finished Good Transfers</a></li>
                        <li class="breadcrumb-item active">Create Transfer</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-plus me-2"></i>Create New Finished Good Transfer
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transfer Details</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('finished-good-transfers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                    <select class="form-select @error('item_id') is-invalid @enderror" 
                                            id="item_id" name="item_id" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="craftsman_id" class="form-label">Craftsman</label>
                                    <select class="form-select @error('craftsman_id') is-invalid @enderror" 
                                            id="craftsman_id" name="craftsman_id">
                                        <option value="">Select Craftsman (Optional)</option>
                                        @foreach($craftsmen as $craftsman)
                                        <option value="{{ $craftsman->id }}" 
                                                {{ old('craftsman_id') == $craftsman->id ? 'selected' : '' }}>
                                            {{ $craftsman->full_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('craftsman_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="from_workshop" class="form-label">From Workshop <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('from_workshop') is-invalid @enderror" 
                                           id="from_workshop" name="from_workshop" 
                                           value="{{ old('from_workshop') }}" 
                                           placeholder="e.g., Main Workshop, Production Line A" required>
                                    @error('from_workshop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="to_location" class="form-label">To Location <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('to_location') is-invalid @enderror" 
                                           id="to_location" name="to_location" 
                                           value="{{ old('to_location') }}" 
                                           placeholder="e.g., Showroom, Storage Room, Quality Control" required>
                                    @error('to_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity') }}" 
                                           min="1" required>
                                    @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transfer_date" class="form-label">Transfer Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('transfer_date') is-invalid @enderror" 
                                           id="transfer_date" name="transfer_date" 
                                           value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                    @error('transfer_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transferred_by" class="form-label">Transferred By</label>
                                    <select class="form-select @error('transferred_by') is-invalid @enderror" 
                                            id="transferred_by" name="transferred_by">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                {{ old('transferred_by') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('transferred_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Add any additional notes about this transfer...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finished-good-transfers.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Create Transfer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for transfer date to today
    const transferDateInput = document.getElementById('transfer_date');
    transferDateInput.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush
