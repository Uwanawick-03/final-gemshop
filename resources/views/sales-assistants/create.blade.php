@extends('layouts.app')

@section('title', 'Add New Sales Assistant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-user-plus me-2"></i>Add New Sales Assistant</h4>
        <div class="small text-muted">Create a new sales assistant profile</div>
    </div>
    <a href="{{ route('sales-assistants.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('sales-assistants.store') }}" class="row g-3">
            @csrf
            
            <!-- Personal Information -->
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input name="first_name" value="{{ old('first_name') }}" 
                       class="form-control @error('first_name') is-invalid @enderror" 
                       placeholder="Enter first name" required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input name="last_name" value="{{ old('last_name') }}" 
                       class="form-control @error('last_name') is-invalid @enderror" 
                       placeholder="Enter last name" required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input name="email" type="email" value="{{ old('email') }}" 
                       class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Enter email address" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input name="phone" value="{{ old('phone') }}" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       placeholder="Enter phone number" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Date of Birth</label>
                <input name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" 
                       class="form-control @error('date_of_birth') is-invalid @enderror">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">National ID</label>
                <input name="national_id" value="{{ old('national_id') }}" 
                       class="form-control @error('national_id') is-invalid @enderror" 
                       placeholder="Enter national ID">
                @error('national_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Address Information -->
            <div class="col-12 mt-4">
                <h5 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
            </div>
            
            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                          rows="2" placeholder="Enter full address">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">City</label>
                <input name="city" value="{{ old('city') }}" 
                       class="form-control @error('city') is-invalid @enderror" 
                       placeholder="Enter city">
                @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input name="country" value="{{ old('country') }}" 
                       class="form-control @error('country') is-invalid @enderror" 
                       placeholder="Enter country">
                @error('country')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Employment Information -->
            <div class="col-12 mt-4">
                <h5 class="text-primary mb-3"><i class="fas fa-briefcase me-2"></i>Employment Information</h5>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Hire Date <span class="text-danger">*</span></label>
                <input name="hire_date" type="date" value="{{ old('hire_date') }}" 
                       class="form-control @error('hire_date') is-invalid @enderror" required>
                @error('hire_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Employment Status <span class="text-danger">*</span></label>
                <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ old('employment_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="on_leave" {{ old('employment_status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
                @error('employment_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <input name="department" value="{{ old('department') }}" 
                       class="form-control @error('department') is-invalid @enderror" 
                       placeholder="e.g. Sales, Marketing">
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Position</label>
                <input name="position" value="{{ old('position') }}" 
                       class="form-control @error('position') is-invalid @enderror" 
                       placeholder="e.g. Sales Assistant, Junior Sales">
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Salary</label>
                <div class="input-group">
                    <span class="input-group-text">{{ getDisplayCurrency()->symbol ?? 'Rs' }}</span>
                    <input name="salary" type="number" step="0.01" value="{{ old('salary') }}" 
                           class="form-control @error('salary') is-invalid @enderror" 
                           placeholder="0.00">
                </div>
                @error('salary')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Active Status</label>
                <div class="form-check mt-2">
                    <input name="is_active" type="checkbox" class="form-check-input" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Active in system</label>
                </div>
            </div>
            
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                          rows="3" placeholder="Additional notes about the sales assistant">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sales-assistants.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Assistant
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
