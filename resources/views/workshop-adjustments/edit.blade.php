@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('workshop-adjustments.index') }}">Workshop Adjustments</a></li>
                        <li class="breadcrumb-item active">Edit Adjustment</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Workshop Adjustment - {{ $workshopAdjustment->reference_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Adjustment Details</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('workshop-adjustments.update', $workshopAdjustment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" value="{{ $workshopAdjustment->reference_number }}" readonly>
                                    <small class="form-text text-muted">Reference number cannot be changed</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="pending" {{ old('status', $workshopAdjustment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $workshopAdjustment->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $workshopAdjustment->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                    <select class="form-select @error('item_id') is-invalid @enderror" 
                                            id="item_id" name="item_id" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ old('item_id', $workshopAdjustment->item_id) == $item->id ? 'selected' : '' }}>
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
                                                {{ old('craftsman_id', $workshopAdjustment->craftsman_id) == $craftsman->id ? 'selected' : '' }}>
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
                                    <label for="adjustment_type" class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('adjustment_type') is-invalid @enderror" 
                                            id="adjustment_type" name="adjustment_type" required>
                                        <option value="">Select Adjustment Type</option>
                                        <option value="material_used" {{ old('adjustment_type', $workshopAdjustment->adjustment_type) == 'material_used' ? 'selected' : '' }}>Material Used</option>
                                        <option value="scrap" {{ old('adjustment_type', $workshopAdjustment->adjustment_type) == 'scrap' ? 'selected' : '' }}>Scrap</option>
                                        <option value="defective" {{ old('adjustment_type', $workshopAdjustment->adjustment_type) == 'defective' ? 'selected' : '' }}>Defective</option>
                                        <option value="correction" {{ old('adjustment_type', $workshopAdjustment->adjustment_type) == 'correction' ? 'selected' : '' }}>Correction</option>
                                    </select>
                                    @error('adjustment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="workshop_location" class="form-label">Workshop Location <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('workshop_location') is-invalid @enderror" 
                                           id="workshop_location" name="workshop_location" 
                                           value="{{ old('workshop_location', $workshopAdjustment->workshop_location) }}" required>
                                    @error('workshop_location')
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
                                           value="{{ old('quantity', $workshopAdjustment->quantity) }}" 
                                           min="1" required>
                                    @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adjustment_date" class="form-label">Adjustment Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('adjustment_date') is-invalid @enderror" 
                                           id="adjustment_date" name="adjustment_date" 
                                           value="{{ old('adjustment_date', $workshopAdjustment->adjustment_date->format('Y-m-d')) }}" required>
                                    @error('adjustment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="approved_by" class="form-label">Approved By</label>
                                    <select class="form-select @error('approved_by') is-invalid @enderror" 
                                            id="approved_by" name="approved_by">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                {{ old('approved_by', $workshopAdjustment->approved_by) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('approved_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="4" 
                                      placeholder="Describe the reason for this adjustment..." required>{{ old('reason', $workshopAdjustment->reason) }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Add any additional notes about this adjustment...">{{ old('notes', $workshopAdjustment->notes) }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('workshop-adjustments.show', $workshopAdjustment) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('workshop-adjustments.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Adjustment
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
    // Set minimum date for adjustment date to today
    const adjustmentDateInput = document.getElementById('adjustment_date');
    adjustmentDateInput.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush
