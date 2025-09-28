@extends('layouts.app')

@section('title', 'Detailed Guide Listing Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-list me-2"></i>Detailed Guide Listing Report
            </h1>
            <p class="text-muted mb-0">Comprehensive guide listing with advanced filtering and search</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.guide-listing') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.guide-listing.export-pdf', ['type' => 'detailed']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.guide-listing.export-excel', ['type' => 'detailed']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.guide-listing.export-csv', ['type' => 'detailed']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.guide-listing.detailed') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search guides...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="employment_status" class="form-label">Employment Status</label>
                        <select class="form-select" id="employment_status" name="employment_status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('employment_status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('employment_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('employment_status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ request('employment_status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="is_active" class="form-label">Active Status</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="">All</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
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
                    <div class="col-md-2 mb-3">
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
                    <div class="col-md-2 mb-3">
                        <label for="license_status" class="form-label">License Status</label>
                        <select class="form-select" id="license_status" name="license_status">
                            <option value="">All</option>
                            <option value="valid" {{ request('license_status') === 'valid' ? 'selected' : '' }}>Valid</option>
                            <option value="expiring_soon" {{ request('license_status') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('license_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="no_license" {{ request('license_status') === 'no_license' ? 'selected' : '' }}>No License</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="joined_from" class="form-label">Joined From</label>
                        <input type="date" class="form-control" id="joined_from" name="joined_from" value="{{ request('joined_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="joined_to" class="form-label">Joined To</label>
                        <input type="date" class="form-control" id="joined_to" name="joined_to" value="{{ request('joined_to') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="daily_rate_min" class="form-label">Min Daily Rate</label>
                        <input type="number" class="form-control" id="daily_rate_min" name="daily_rate_min" 
                               value="{{ request('daily_rate_min') }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="daily_rate_max" class="form-label">Max Daily Rate</label>
                        <input type="number" class="form-control" id="daily_rate_max" name="daily_rate_max" 
                               value="{{ request('daily_rate_max') }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort_by" class="form-label">Sort By</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                            <option value="joined_date" {{ request('sort_by') === 'joined_date' ? 'selected' : '' }}>Joined Date</option>
                            <option value="first_name" {{ request('sort_by') === 'first_name' ? 'selected' : '' }}>First Name</option>
                            <option value="last_name" {{ request('sort_by') === 'last_name' ? 'selected' : '' }}>Last Name</option>
                            <option value="daily_rate" {{ request('sort_by') === 'daily_rate' ? 'selected' : '' }}>Daily Rate</option>
                            <option value="license_expiry" {{ request('sort_by') === 'license_expiry' ? 'selected' : '' }}>License Expiry</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.guide-listing.detailed') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Results
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $guides->total() }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Guides
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $guides->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Valid Licenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $guides->filter(function($guide) { return $guide->license_status === 'Valid'; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
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
                                Avg Daily Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($guides->whereNotNull('daily_rate')->avg('daily_rate'), 2) }}
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

    <!-- Guides Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Guide Listing
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Guide Code</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Languages</th>
                            <th>Service Areas</th>
                            <th>Employment Status</th>
                            <th>Daily Rate</th>
                            <th>License Status</th>
                            <th>Joined Date</th>
                            <th>Years of Service</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guides as $guide)
                        <tr>
                            <td>
                                <strong>{{ $guide->guide_code }}</strong>
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
                                <div>
                                    @if($guide->email)
                                        <div><i class="fas fa-envelope me-1"></i>{{ $guide->email }}</div>
                                    @endif
                                    <div><i class="fas fa-phone me-1"></i>{{ $guide->phone }}</div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($guide->city)
                                        <div>{{ $guide->city }}</div>
                                    @endif
                                    @if($guide->country)
                                        <div class="text-muted">{{ $guide->country }}</div>
                                    @endif
                                </div>
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
                                <span class="badge {{ $guide->employment_status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
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
                                <span class="badge {{ $guide->license_status_badge }}">
                                    {{ $guide->license_status }}
                                </span>
                                @if($guide->license_expiry)
                                    <div class="text-xs text-muted">
                                        Expires: {{ $guide->license_expiry->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($guide->joined_date)
                                    {{ $guide->joined_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $guide->years_of_service }} years</span>
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
                            <td colspan="12" class="text-center text-muted">No guides found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($guides->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $guides->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
