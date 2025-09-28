@extends('layouts.app')

@section('title', 'Guide Performance Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line me-2"></i>Guide Performance Report
            </h1>
            <p class="text-muted mb-0">Comprehensive performance analysis of tour guides</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.guide-listing') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.guide-listing.export-pdf', ['type' => 'performance']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.guide-listing.export-excel', ['type' => 'performance']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.guide-listing.export-csv', ['type' => 'performance']) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.guide-listing.performance') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="employment_status" class="form-label">Employment Status</label>
                        <select class="form-select" id="employment_status" name="employment_status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('employment_status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('employment_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('employment_status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ request('employment_status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="is_active" class="form-label">Active Status</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="">All</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="language" class="form-label">Language</label>
                        <select class="form-select" id="language" name="language">
                            <option value="">All Languages</option>
                            @foreach($languages as $language)
                            <option value="{{ $language }}" {{ request('language') === $language ? 'selected' : '' }}>
                                {{ $language }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="service_area" class="form-label">Service Area</label>
                        <select class="form-select" id="service_area" name="service_area">
                            <option value="">All Areas</option>
                            @foreach($serviceAreas as $area)
                            <option value="{{ $area }}" {{ request('service_area') === $area ? 'selected' : '' }}>
                                {{ $area }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.guide-listing.performance') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Guides
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $performanceData->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Avg Performance Score
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($performanceData->avg('metrics.performance_score'), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg Years of Service
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($performanceData->avg('metrics.years_of_service'), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg Daily Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($performanceData->avg('guide.daily_rate'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Distribution -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Performance Score Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h3 class="text-success">{{ $performanceData->where('metrics.performance_score', '>=', 80)->count() }}</h3>
                            <p class="text-muted mb-0">Excellent (80-100)</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-info">{{ $performanceData->whereBetween('metrics.performance_score', [60, 79])->count() }}</h3>
                            <p class="text-muted mb-0">Good (60-79)</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-warning">{{ $performanceData->whereBetween('metrics.performance_score', [40, 59])->count() }}</h3>
                            <p class="text-muted mb-0">Fair (40-59)</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-danger">{{ $performanceData->where('metrics.performance_score', '<', 40)->count() }}</h3>
                            <p class="text-muted mb-0">Needs Improvement (<40)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Guide Performance Rankings
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Guide</th>
                            <th>Code</th>
                            <th>Performance Score</th>
                            <th>Years of Service</th>
                            <th>Performance Rating</th>
                            <th>Employment Status</th>
                            <th>License Status</th>
                            <th>Daily Rate</th>
                            <th>Languages</th>
                            <th>Service Areas</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performanceData as $index => $item)
                        @php
                            $guide = $item['guide'];
                            $metrics = $item['metrics'];
                        @endphp
                        <tr>
                            <td>
                                @if($index < 3)
                                    <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }}">
                                        #{{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $guide->full_name }}</strong>
                                    @if($guide->is_active)
                                        <span class="badge bg-success ms-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Inactive</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $guide->guide_code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $metrics['performance_score'] >= 80 ? 'success' : ($metrics['performance_score'] >= 60 ? 'info' : ($metrics['performance_score'] >= 40 ? 'warning' : 'danger')) }}" 
                                             role="progressbar" style="width: {{ $metrics['performance_score'] }}%">
                                            {{ number_format($metrics['performance_score'], 1) }}
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $metrics['performance_score'] >= 80 ? 'success' : ($metrics['performance_score'] >= 60 ? 'info' : ($metrics['performance_score'] >= 40 ? 'warning' : 'danger')) }}">
                                        {{ number_format($metrics['performance_score'], 1) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $metrics['years_of_service'] }} years</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $metrics['performance_rating']['color'] }}">
                                    <i class="fas fa-{{ $metrics['performance_rating']['icon'] }} me-1"></i>
                                    {{ $metrics['performance_rating']['rating'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $guide->employment_status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $guide->license_status_badge }}">
                                    {{ $guide->license_status }}
                                </span>
                            </td>
                            <td>
                                @if($guide->daily_rate)
                                    <strong>${{ number_format($guide->daily_rate, 2) }}/day</strong>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                @if($guide->languages_list)
                                    <span class="badge bg-info">{{ $guide->languages_list }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                @if($guide->service_areas_list)
                                    <span class="badge bg-primary">{{ $guide->service_areas_list }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('tour-guides.show', $guide) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tour-guides.edit', $guide) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No performance data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Performance Metrics Explanation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Performance Score Calculation:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Years of Service (max 25 points)</li>
                                <li><i class="fas fa-check text-success me-2"></i>License Status (max 20 points)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Employment Status (max 15 points)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Activity Status (max 10 points)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Tour Performance (max 30 points)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Performance Rating Categories:</h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-success me-2">Master Guide</span>100+ tours, 4.5+ rating</li>
                                <li><span class="badge bg-primary me-2">Expert Guide</span>50+ tours, 4.0+ rating</li>
                                <li><span class="badge bg-info me-2">Experienced Guide</span>25+ tours, 3.5+ rating</li>
                                <li><span class="badge bg-warning me-2">Developing Guide</span>10+ tours</li>
                                <li><span class="badge bg-secondary me-2">New Guide</span>Less than 10 tours</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
