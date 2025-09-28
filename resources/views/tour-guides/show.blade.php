@extends('layouts.app')

@section('title', 'Tour Guide Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-map-marked-alt me-2"></i>{{ $tourGuide->full_name }}</h2>
    <div>
        <a href="{{ route('tour-guides.edit', $tourGuide) }}" class="btn btn-secondary me-2">
            <i class="fas fa-edit me-2"></i>Edit Guide
        </a>
        <a href="{{ route('tour-guides.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Guides
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Guide Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <span class="fs-1">{{ substr($tourGuide->first_name, 0, 1) }}{{ substr($tourGuide->last_name, 0, 1) }}</span>
                </div>
                
                <h4 class="mb-1">{{ $tourGuide->full_name }}</h4>
                <p class="text-muted mb-2">{{ $tourGuide->guide_code }}</p>

                <!-- Performance Rating -->
                <div class="mb-3">
                    @php $performance = $tourGuide->performance_rating; @endphp
                    <div class="mb-3">
                        <i class="fas fa-{{ $performance['icon'] }} fa-3x text-{{ $performance['color'] }}"></i>
                    </div>
                    <h4 class="text-{{ $performance['color'] }}">{{ $performance['rating'] }}</h4>
                    <p class="text-muted mb-0">Based on tour completion and ratings</p>
                </div>

                <!-- Status Badges -->
                <div class="mb-3">
                    <span class="badge {{ $tourGuide->employment_status_badge }} mb-1">
                        {{ ucfirst(str_replace('_', ' ', $tourGuide->employment_status)) }}
                    </span>
                    @if($tourGuide->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>

                <!-- License Status -->
                <div class="mb-3">
                    <span class="badge {{ $tourGuide->license_status_badge }}">
                        {{ $tourGuide->license_status }}
                    </span>
                    @if($tourGuide->license_expiry)
                        <div class="small text-muted mt-1">Expires: {{ $tourGuide->license_expiry->format('M d, Y') }}</div>
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
                            <h5 class="text-primary mb-1">{{ $tourGuide->years_of_service }}</h5>
                            <small class="text-muted">Years of Service</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-info mb-1">{{ $tourGuide->total_tours_conducted + $tourGuide->total_tours_in_progress + $tourGuide->total_tours_pending }}</h5>
                        <small class="text-muted">Total Tours</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-success mb-1">{{ $tourGuide->total_tours_conducted }}</h5>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning mb-1">{{ displayAmount($tourGuide->total_earnings) }}</h5>
                        <small class="text-muted">Total Earned</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Assign New Tour
                    </a>
                    <a href="#" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-star me-2"></i>View Reviews
                    </a>
                    <a href="{{ route('tour-guides.edit', $tourGuide) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
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
                        @if($tourGuide->email)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-1 text-muted"></i>
                                    <a href="mailto:{{ $tourGuide->email }}">{{ $tourGuide->email }}</a>
                                </p>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="mb-0">
                                <i class="fas fa-phone me-1 text-muted"></i>
                                <a href="tel:{{ $tourGuide->phone }}">{{ $tourGuide->phone }}</a>
                            </p>
                        </div>
                        @if($tourGuide->date_of_birth)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="mb-0">
                                    <i class="fas fa-birthday-cake me-1 text-muted"></i>
                                    {{ $tourGuide->date_of_birth->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        @if($tourGuide->gender)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <p class="mb-0">
                                    <i class="fas fa-user me-1 text-muted"></i>
                                    {{ ucfirst($tourGuide->gender) }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($tourGuide->joined_date)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Joined Date</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                    {{ $tourGuide->joined_date->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        @if($tourGuide->national_id)
                            <div class="mb-3">
                                <label class="form-label fw-bold">National ID</label>
                                <p class="mb-0">
                                    <i class="fas fa-id-card me-1 text-muted"></i>
                                    {{ $tourGuide->national_id }}
                                </p>
                            </div>
                        @endif
                        @if($tourGuide->days_in_system)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Days in System</label>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-1 text-muted"></i>
                                    {{ number_format($tourGuide->days_in_system) }} days
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($tourGuide->full_address)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                            {{ $tourGuide->full_address }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Professional Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2 text-success"></i>Professional Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Languages</label>
                            @if($tourGuide->languages_list)
                                <div>
                                    @foreach(explode(', ', $tourGuide->languages_list) as $lang)
                                        <span class="badge bg-info me-1 mb-1">{{ trim($lang) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">No languages specified</p>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Service Areas</label>
                            @if($tourGuide->service_areas_list)
                                <div>
                                    @foreach(explode(', ', $tourGuide->service_areas_list) as $area)
                                        <span class="badge bg-secondary me-1 mb-1">{{ trim($area) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">No service areas specified</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Daily Rate</label>
                            <p class="mb-0">
                                <i class="fas fa-dollar-sign me-1 text-muted"></i>
                                {{ $tourGuide->formatted_daily_rate }}
                            </p>
                        </div>
                        
                        @if($tourGuide->license_number)
                            <div class="mb-3">
                                <label class="form-label fw-bold">License Number</label>
                                <p class="mb-0">
                                    <i class="fas fa-certificate me-1 text-muted"></i>
                                    {{ $tourGuide->license_number }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Performance Analytics
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-success mb-1">{{ $tourGuide->total_tours_conducted }}</h5>
                            <small class="text-muted">Tours Completed</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-warning mb-1">{{ $tourGuide->total_tours_in_progress }}</h5>
                            <small class="text-muted">In Progress</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-info mb-1">{{ number_format($tourGuide->average_tour_rating, 1) }}</h5>
                            <small class="text-muted">Avg Rating</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-primary mb-1">{{ displayAmount($tourGuide->total_earnings) }}</h5>
                            <small class="text-muted">Total Earned</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">Pending Tours</span>
                            <strong>{{ $tourGuide->total_tours_pending }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">This Month</span>
                            <strong>{{ displayAmount($tourGuide->getMonthlyEarnings()) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">Completion Rate</span>
                            <strong>{{ $tourGuide->total_tours_conducted > 0 ? round(($tourGuide->total_tours_conducted / ($tourGuide->total_tours_conducted + $tourGuide->total_tours_pending)) * 100, 1) : 0 }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($tourGuide->notes)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i>Notes
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $tourGuide->notes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection