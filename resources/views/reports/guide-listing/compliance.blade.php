@extends('layouts.app')

@section('title', 'Guide Compliance Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shield-alt me-2"></i>Guide Compliance Report
            </h1>
            <p class="text-muted mb-0">License compliance and regulatory status tracking</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.guide-listing') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.guide-listing.export-pdf', ['type' => 'compliance']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.guide-listing.export-excel', ['type' => 'compliance']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.guide-listing.export-csv', ['type' => 'compliance']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.guide-listing.compliance') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="license_status" class="form-label">License Status</label>
                        <select class="form-select" id="license_status" name="license_status">
                            <option value="">All Statuses</option>
                            <option value="valid" {{ request('license_status') === 'valid' ? 'selected' : '' }}>Valid</option>
                            <option value="expiring_soon" {{ request('license_status') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('license_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="no_license" {{ request('license_status') === 'no_license' ? 'selected' : '' }}>No License</option>
                        </select>
                    </div>
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
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.guide-listing.compliance') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Compliance Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Valid Licenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $licenseGroups['valid']->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Expiring Soon
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $licenseGroups['expiring_soon']->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Expired Licenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $licenseGroups['expired']->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                No License Info
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $licenseGroups['no_license']->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- License Status Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>License Status Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <div class="text-success" style="font-size: 3rem;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h4 class="text-success">{{ $licenseGroups['valid']->count() }}</h4>
                                <p class="text-muted mb-0">Valid Licenses</p>
                                <small class="text-muted">{{ $guides->count() > 0 ? number_format(($licenseGroups['valid']->count() / $guides->count()) * 100, 1) : 0 }}% of total</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <div class="text-warning" style="font-size: 3rem;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h4 class="text-warning">{{ $licenseGroups['expiring_soon']->count() }}</h4>
                                <p class="text-muted mb-0">Expiring Soon</p>
                                <small class="text-muted">{{ $guides->count() > 0 ? number_format(($licenseGroups['expiring_soon']->count() / $guides->count()) * 100, 1) : 0 }}% of total</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <div class="text-danger" style="font-size: 3rem;">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <h4 class="text-danger">{{ $licenseGroups['expired']->count() }}</h4>
                                <p class="text-muted mb-0">Expired Licenses</p>
                                <small class="text-muted">{{ $guides->count() > 0 ? number_format(($licenseGroups['expired']->count() / $guides->count()) * 100, 1) : 0 }}% of total</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <div class="text-secondary" style="font-size: 3rem;">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <h4 class="text-secondary">{{ $licenseGroups['no_license']->count() }}</h4>
                                <p class="text-muted mb-0">No License Info</p>
                                <small class="text-muted">{{ $guides->count() > 0 ? number_format(($licenseGroups['no_license']->count() / $guides->count()) * 100, 1) : 0 }}% of total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- License Groups -->
    @foreach(['expired' => 'danger', 'expiring_soon' => 'warning', 'no_license' => 'secondary', 'valid' => 'success'] as $status => $color)
    @if($licenseGroups[$status]->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-{{ $color }} text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-{{ $status === 'valid' ? 'check-circle' : ($status === 'expiring_soon' ? 'exclamation-triangle' : ($status === 'expired' ? 'times-circle' : 'question-circle')) }} me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $status)) }} Licenses ({{ $licenseGroups[$status]->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Guide</th>
                                    <th>Code</th>
                                    <th>License Number</th>
                                    <th>Expiry Date</th>
                                    <th>Days Until Expiry</th>
                                    <th>Employment Status</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($licenseGroups[$status] as $guide)
                                <tr>
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
                                        @if($guide->license_number)
                                            {{ $guide->license_number }}
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guide->license_expiry)
                                            {{ $guide->license_expiry->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guide->license_expiry)
                                            @php
                                                $daysUntilExpiry = $guide->license_expiry->diffInDays(now(), false);
                                            @endphp
                                            @if($daysUntilExpiry < 0)
                                                <span class="badge bg-danger">Expired {{ abs($daysUntilExpiry) }} days ago</span>
                                            @elseif($daysUntilExpiry <= 30)
                                                <span class="badge bg-warning">{{ $daysUntilExpiry }} days</span>
                                            @else
                                                <span class="badge bg-success">{{ $daysUntilExpiry }} days</span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $guide->employment_status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
                                        </span>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Compliance Recommendations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb me-2"></i>Compliance Recommendations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>Immediate Actions Required
                            </h6>
                            <ul class="list-unstyled">
                                @if($licenseGroups['expired']->count() > 0)
                                <li><i class="fas fa-times text-danger me-2"></i>{{ $licenseGroups['expired']->count() }} guides have expired licenses - contact immediately</li>
                                @endif
                                @if($licenseGroups['expiring_soon']->count() > 0)
                                <li><i class="fas fa-clock text-warning me-2"></i>{{ $licenseGroups['expiring_soon']->count() }} guides have licenses expiring within 30 days - send renewal reminders</li>
                                @endif
                                @if($licenseGroups['no_license']->count() > 0)
                                <li><i class="fas fa-question text-info me-2"></i>{{ $licenseGroups['no_license']->count() }} guides have no license information - request license details</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="fas fa-check-circle me-1"></i>Compliance Status
                            </h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>{{ $licenseGroups['valid']->count() }} guides have valid licenses</li>
                                @php
                                    $complianceRate = $guides->count() > 0 ? ($licenseGroups['valid']->count() / $guides->count()) * 100 : 0;
                                @endphp
                                <li><i class="fas fa-chart-line text-info me-2"></i>Overall compliance rate: {{ number_format($complianceRate, 1) }}%</li>
                                @if($complianceRate >= 90)
                                    <li><i class="fas fa-star text-warning me-2"></i>Excellent compliance rate!</li>
                                @elseif($complianceRate >= 75)
                                    <li><i class="fas fa-thumbs-up text-primary me-2"></i>Good compliance rate</li>
                                @else
                                    <li><i class="fas fa-exclamation text-warning me-2"></i>Compliance rate needs improvement</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
