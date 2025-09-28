@extends('layouts.app')

@section('title', 'Craftsman Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-hammer me-2"></i>{{ $craftsman->full_name }}</h2>
    <div>
        <a href="{{ route('craftsmen.edit', $craftsman) }}" class="btn btn-secondary me-2">
            <i class="fas fa-edit me-2"></i>Edit Craftsman
        </a>
        <a href="{{ route('craftsmen.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Craftsmen
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Craftsman Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <span class="fs-1">{{ substr($craftsman->first_name, 0, 1) }}{{ substr($craftsman->last_name, 0, 1) }}</span>
                </div>
                
                <h4 class="mb-1">{{ $craftsman->full_name }}</h4>
                <p class="text-muted mb-2">{{ $craftsman->craftsman_code }}</p>
                
                @if($craftsman->primary_skill)
                    <p class="mb-3">
                        <i class="fas fa-tools me-1"></i>
                        {{ $craftsman->primary_skill }}
                    </p>
                @endif

                <!-- Performance Rating -->
                <div class="mb-3">
                    @php $performance = $craftsman->performance_rating; @endphp
                    <div class="mb-3">
                        <i class="fas fa-{{ $performance['icon'] }} fa-3x text-{{ $performance['color'] }}"></i>
                    </div>
                    <h4 class="text-{{ $performance['color'] }}">{{ $performance['rating'] }}</h4>
                    <p class="text-muted mb-0">Based on job completion and quality</p>
                </div>

                <!-- Status Badges -->
                <div class="mb-3">
                    <span class="badge {{ $craftsman->employment_status_badge }} mb-1">
                        {{ ucfirst(str_replace('_', ' ', $craftsman->employment_status)) }}
                    </span>
                    @if($craftsman->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h5 class="text-primary mb-1">{{ $craftsman->years_of_service }}</h5>
                            <small class="text-muted">Years of Service</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-info mb-1">{{ $craftsman->total_jobs_completed }}</h5>
                        <small class="text-muted">Jobs Completed</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-success mb-1">{{ $craftsman->total_jobs_in_progress }}</h5>
                            <small class="text-muted">In Progress</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning mb-1">{{ displayAmount($craftsman->total_commissions_earned) }}</h5>
                        <small class="text-muted">Total Earned</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($craftsman->email)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-1 text-muted"></i>
                                    <a href="mailto:{{ $craftsman->email }}">{{ $craftsman->email }}</a>
                                </p>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="mb-0">
                                <i class="fas fa-phone me-1 text-muted"></i>
                                <a href="tel:{{ $craftsman->phone }}">{{ $craftsman->phone }}</a>
                            </p>
                        </div>
                        @if($craftsman->date_of_birth)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="mb-0">
                                    <i class="fas fa-birthday-cake me-1 text-muted"></i>
                                    {{ $craftsman->date_of_birth->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($craftsman->joined_date)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Joined Date</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                    {{ $craftsman->joined_date->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        @if($craftsman->national_id)
                            <div class="mb-3">
                                <label class="form-label fw-bold">National ID</label>
                                <p class="mb-0">
                                    <i class="fas fa-id-card me-1 text-muted"></i>
                                    {{ $craftsman->national_id }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($craftsman->full_address)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                            {{ $craftsman->full_address }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Skills and Rates -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-tools me-2 text-warning"></i>Skills and Rates
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Primary Skill</label>
                            <p class="mb-0">
                                @if($craftsman->primary_skill)
                                    <span class="badge bg-primary fs-6">{{ $craftsman->primary_skill }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($craftsman->skills_list)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Additional Skills</label>
                                <div>
                                    @foreach(explode(', ', $craftsman->skills_list) as $skill)
                                        <span class="badge bg-secondary me-1 mb-1">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hourly Rate</label>
                            <p class="mb-0">
                                <i class="fas fa-dollar-sign me-1 text-muted"></i>
                                {{ $craftsman->formatted_hourly_rate }}
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Commission Rate</label>
                            <p class="mb-0">
                                <i class="fas fa-percentage me-1 text-muted"></i>
                                {{ $craftsman->formatted_commission_rate }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection