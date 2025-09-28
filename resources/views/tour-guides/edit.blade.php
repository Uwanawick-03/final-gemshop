@extends('layouts.app')

@section('title', 'Edit Tour Guide')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Tour Guide: {{ $tourGuide->full_name }}</h2>
    <div>
        <a href="{{ route('tour-guides.show', $tourGuide) }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-eye me-2"></i>View Profile
        </a>
        <a href="{{ route('tour-guides.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Tour Guides
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-user-edit me-2"></i>Edit Tour Guide Information
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('tour-guides.update', $tourGuide) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               id="first_name" name="first_name" value="{{ old('first_name', $tourGuide->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               id="last_name" name="last_name" value="{{ old('last_name', $tourGuide->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $tourGuide->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $tourGuide->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="languages" class="form-label">Languages</label>
                        <input type="text" class="form-control @error('languages') is-invalid @enderror" 
                               id="languages" name="languages" value="{{ old('languages', $tourGuide->languages_list) }}"
                               placeholder="English, Spanish, French (comma separated)">
                        @error('languages')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="service_areas" class="form-label">Service Areas</label>
                        <input type="text" class="form-control @error('service_areas') is-invalid @enderror" 
                               id="service_areas" name="service_areas" value="{{ old('service_areas', $tourGuide->service_areas_list) }}"
                               placeholder="Downtown, Beach Area (comma separated)">
                        @error('service_areas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="daily_rate" class="form-label">Daily Rate</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('daily_rate') is-invalid @enderror" 
                                   id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $tourGuide->daily_rate) }}">
                            <span class="input-group-text">/day</span>
                        </div>
                        @error('daily_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employment_status" class="form-label">Employment Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('employment_status') is-invalid @enderror" 
                                id="employment_status" name="employment_status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('employment_status', $tourGuide->employment_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('employment_status', $tourGuide->employment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ old('employment_status', $tourGuide->employment_status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ old('employment_status', $tourGuide->employment_status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                           {{ old('is_active', $tourGuide->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active Tour Guide
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tour-guides.show', $tourGuide) }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Tour Guide
                </button>
            </div>
        </form>
    </div>
</div>
@endsection