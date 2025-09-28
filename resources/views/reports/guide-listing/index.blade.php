@extends('layouts.app')

@section('title', 'Guide Listing Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>Guide Listing Report
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of tour guide management and performance</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.guide-listing.export-pdf', ['type' => 'summary']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.guide-listing.export-excel') }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.guide-listing.export-csv') }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Guides -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Guides
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_guides'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['active_guides'] }} active
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Employment -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Employment
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['active_employed'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['on_leave'] }} on leave
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- License Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Valid Licenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['valid_licenses'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['expiring_licenses'] }} expiring soon
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Daily Rate -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg Daily Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($summary['avg_daily_rate'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['new_guides_this_month'] }} new this month
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

    <!-- Additional Summary Cards -->
    <div class="row mb-4">
        <!-- Expired Licenses -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Expired Licenses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['expired_licenses'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['no_licenses'] }} no license
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Inactive/Terminated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['terminated'] }}
                            </div>
                            <div class="text-xs text-muted">
                                Terminated guides
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guide Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Guide Alerts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Expiring Licenses -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-clock me-1"></i>Licenses Expiring Soon (30 days)
                            </h6>
                            @if($guideAlerts['expiring_licenses']->count() > 0)
                                @foreach($guideAlerts['expiring_licenses'] as $guide)
                                <div class="alert alert-warning alert-sm mb-2">
                                    <strong>{{ $guide->full_name }}</strong><br>
                                    <small>{{ $guide->guide_code }} - Expires {{ $guide->license_expiry->format('M d, Y') }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No licenses expiring soon</p>
                            @endif
                        </div>

                        <!-- Expired Licenses -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-times me-1"></i>Expired Licenses
                            </h6>
                            @if($guideAlerts['expired_licenses']->count() > 0)
                                @foreach($guideAlerts['expired_licenses'] as $guide)
                                <div class="alert alert-danger alert-sm mb-2">
                                    <strong>{{ $guide->full_name }}</strong><br>
                                    <small>{{ $guide->guide_code }} - Expired {{ $guide->license_expiry->format('M d, Y') }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No expired licenses</p>
                            @endif
                        </div>

                        <!-- No Licenses -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-question me-1"></i>No License Information
                            </h6>
                            @if($guideAlerts['no_licenses']->count() > 0)
                                @foreach($guideAlerts['no_licenses'] as $guide)
                                <div class="alert alert-info alert-sm mb-2">
                                    <strong>{{ $guide->full_name }}</strong><br>
                                    <small>{{ $guide->guide_code }} - No license on file</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">All guides have license information</p>
                            @endif
                        </div>

                        <!-- On Leave -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-secondary mb-2">
                                <i class="fas fa-bed me-1"></i>Currently on Leave
                            </h6>
                            @if($guideAlerts['on_leave']->count() > 0)
                                @foreach($guideAlerts['on_leave'] as $guide)
                                <div class="alert alert-secondary alert-sm mb-2">
                                    <strong>{{ $guide->full_name }}</strong><br>
                                    <small>{{ $guide->guide_code }} - On leave</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No guides currently on leave</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Top Performing Guides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Guide</th>
                                    <th>Code</th>
                                    <th>Years of Service</th>
                                    <th>Languages</th>
                                    <th>Service Areas</th>
                                    <th>Daily Rate</th>
                                    <th>Performance Rating</th>
                                    <th>License Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPerformers as $guide)
                                <tr>
                                    <td>
                                        <strong>{{ $guide->full_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $guide->guide_code }}</span>
                                    </td>
                                    <td>{{ $guide->years_of_service }} years</td>
                                    <td>{{ $guide->languages_list }}</td>
                                    <td>{{ $guide->service_areas_list }}</td>
                                    <td>{{ $guide->formatted_daily_rate }}</td>
                                    <td>
                                        <span class="badge bg-{{ $guide->performance_metrics['performance_rating']['color'] }}">
                                            <i class="fas fa-{{ $guide->performance_metrics['performance_rating']['icon'] }} me-1"></i>
                                            {{ $guide->performance_metrics['performance_rating']['rating'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $guide->license_status_badge }}">
                                            {{ $guide->license_status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No guides found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Charts -->
    <div class="row mb-4">
        <!-- Guide Distribution by Location -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Guide Distribution by Location
                    </h6>
                </div>
                <div class="card-body">
                    @if($guideDistribution->count() > 0)
                        @foreach($guideDistribution as $location)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ $location->city }}, {{ $location->country }}</span>
                                <span class="badge bg-primary">{{ $location->count }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($location->count / $guideDistribution->max('count')) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No location data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Language Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-language me-2"></i>Language Distribution
                    </h6>
                </div>
                <div class="card-body">
                    @if($languageDistribution->count() > 0)
                        @foreach($languageDistribution as $lang)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ $lang['language'] }}</span>
                                <span class="badge bg-info">{{ $lang['count'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" 
                                     style="width: {{ ($lang['count'] / $languageDistribution->max('count')) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No language data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Employment Status Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Employment Status Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h3 class="text-success">{{ $employmentSummary['active'] }}</h3>
                            <p class="text-muted mb-0">Active</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-secondary">{{ $employmentSummary['inactive'] }}</h3>
                            <p class="text-muted mb-0">Inactive</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-warning">{{ $employmentSummary['on_leave'] }}</h3>
                            <p class="text-muted mb-0">On Leave</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-danger">{{ $employmentSummary['terminated'] }}</h3>
                            <p class="text-muted mb-0">Terminated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.guide-listing.detailed') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.guide-listing.performance') }}" class="btn btn-success btn-block">
                                <i class="fas fa-chart-line me-1"></i>Performance Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.guide-listing.compliance') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-shield-alt me-1"></i>Compliance Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('tour-guides.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-cog me-1"></i>Manage Guides
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
