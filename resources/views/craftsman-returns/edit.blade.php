@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('craftsman-returns.index') }}">Craftsman Returns</a></li>
                        <li class="breadcrumb-item active">Edit Return</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Craftsman Return - {{ $craftsmanReturn->return_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Return Details</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('craftsman-returns.update', $craftsmanReturn) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_number" class="form-label">Return Number</label>
                                    <input type="text" class="form-control" value="{{ $craftsmanReturn->return_number }}" readonly>
                                    <small class="form-text text-muted">Return number cannot be changed</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="pending" {{ old('status', $craftsmanReturn->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $craftsmanReturn->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="completed" {{ old('status', $craftsmanReturn->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="rejected" {{ old('status', $craftsmanReturn->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                    <label for="craftsman_id" class="form-label">Craftsman <span class="text-danger">*</span></label>
                                    <select class="form-select @error('craftsman_id') is-invalid @enderror" 
                                            id="craftsman_id" name="craftsman_id" required>
                                        <option value="">Select Craftsman</option>
                                        @foreach($craftsmen as $craftsman)
                                        <option value="{{ $craftsman->id }}" 
                                                {{ old('craftsman_id', $craftsmanReturn->craftsman_id) == $craftsman->id ? 'selected' : '' }}>
                                            {{ $craftsman->full_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('craftsman_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                    <select class="form-select @error('item_id') is-invalid @enderror" 
                                            id="item_id" name="item_id" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ old('item_id', $craftsmanReturn->item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_type" class="form-label">Return Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('return_type') is-invalid @enderror" 
                                            id="return_type" name="return_type" required>
                                        <option value="">Select Return Type</option>
                                        <option value="defective" {{ old('return_type', $craftsmanReturn->return_type) == 'defective' ? 'selected' : '' }}>Defective</option>
                                        <option value="unused_material" {{ old('return_type', $craftsmanReturn->return_type) == 'unused_material' ? 'selected' : '' }}>Unused Material</option>
                                        <option value="excess" {{ old('return_type', $craftsmanReturn->return_type) == 'excess' ? 'selected' : '' }}>Excess</option>
                                        <option value="quality_issue" {{ old('return_type', $craftsmanReturn->return_type) == 'quality_issue' ? 'selected' : '' }}>Quality Issue</option>
                                    </select>
                                    @error('return_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', $craftsmanReturn->quantity) }}" 
                                           min="1" required>
                                    @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_date" class="form-label">Return Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('return_date') is-invalid @enderror" 
                                           id="return_date" name="return_date" 
                                           value="{{ old('return_date', $craftsmanReturn->return_date->format('Y-m-d')) }}" required>
                                    @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="processed_by" class="form-label">Processed By</label>
                                    <select class="form-select @error('processed_by') is-invalid @enderror" 
                                            id="processed_by" name="processed_by">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                {{ old('processed_by', $craftsmanReturn->processed_by) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('processed_by')
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
                                                {{ old('approved_by', $craftsmanReturn->approved_by) == $user->id ? 'selected' : '' }}>
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
                                      placeholder="Describe the reason for this return..." required>{{ old('reason', $craftsmanReturn->reason) }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Add any additional notes about this return...">{{ old('notes', $craftsmanReturn->notes) }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('craftsman-returns.show', $craftsmanReturn) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('craftsman-returns.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Return
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
    // Set minimum date for return date to today
    const returnDateInput = document.getElementById('return_date');
    returnDateInput.min = new Date().toISOString().split('T')[0];
});
</script>
@endpush
