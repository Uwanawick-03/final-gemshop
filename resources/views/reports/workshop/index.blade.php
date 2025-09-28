@extends('layouts.app')

@section('title', 'Workshop Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-hammer me-2"></i>Workshop Report
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of workshop operations and performance</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'summary']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel') }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv') }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Job Issues -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Job Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_job_issues'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['open_job_issues'] }} open, {{ $summary['resolved_job_issues'] }} resolved
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workshop Adjustments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Adjustments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_adjustments'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['pending_adjustments'] }} pending, {{ $summary['approved_adjustments'] }} approved
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finished Good Transfers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Transfers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_transfers'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['completed_transfers'] }} completed, {{ $summary['quality_check_passed'] }} quality passed
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Craftsman Returns -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Returns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_returns'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['pending_returns'] }} pending, {{ $summary['completed_returns'] }} completed
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-left fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Summary Cards -->
    <div class="row mb-4">
        <!-- MTCs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                MTCs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_mtcs'] }}
                            </div>
                            <div class="text-xs text-muted">
                                {{ $summary['active_mtcs'] }} active, {{ $summary['expired_mtcs'] }} expired
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quality Pass Rate -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Quality Pass Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($qualityMetrics['quality_pass_rate'], 1) }}%
                            </div>
                            <div class="text-xs text-muted">
                                {{ $qualityMetrics['passed_quality_checks'] }}/{{ $qualityMetrics['total_quality_checks'] }} passed
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-award fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Craftsmen -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Craftsmen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['total_craftsmen'] }}
                            </div>
                            <div class="text-xs text-muted">
                                Currently active craftsmen
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Productivity -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Today's Activity
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $workshopProductivity['issues_resolved_today'] + $workshopProductivity['adjustments_today'] + $workshopProductivity['transfers_today'] }}
                            </div>
                            <div class="text-xs text-muted">
                                Issues resolved, adjustments, transfers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workshop Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Workshop Alerts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Urgent Job Issues -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-fire me-1"></i>Urgent Job Issues
                            </h6>
                            @if($workshopAlerts['urgent_job_issues']->count() > 0)
                                @foreach($workshopAlerts['urgent_job_issues'] as $issue)
                                <div class="alert alert-danger alert-sm mb-2">
                                    <strong>Issue #{{ $issue->job_number }}</strong><br>
                                    <small>{{ $issue->item->name ?? 'N/A' }} - {{ $issue->issue_type }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No urgent job issues</p>
                            @endif
                        </div>

                        <!-- Overdue Job Issues -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-clock me-1"></i>Overdue Issues
                            </h6>
                            @if($workshopAlerts['overdue_job_issues']->count() > 0)
                                @foreach($workshopAlerts['overdue_job_issues'] as $issue)
                                <div class="alert alert-warning alert-sm mb-2">
                                    <strong>Issue #{{ $issue->job_number }}</strong><br>
                                    <small>{{ $issue->item->name ?? 'N/A' }} - {{ $issue->issue_date->diffForHumans() }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No overdue issues</p>
                            @endif
                        </div>

                        <!-- Pending Adjustments -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-balance-scale me-1"></i>Pending Adjustments
                            </h6>
                            @if($workshopAlerts['pending_adjustments']->count() > 0)
                                @foreach($workshopAlerts['pending_adjustments'] as $adjustment)
                                <div class="alert alert-info alert-sm mb-2">
                                    <strong>Adjustment #{{ $adjustment->reference_number }}</strong><br>
                                    <small>{{ $adjustment->item->name ?? 'N/A' }} - {{ $adjustment->adjustment_type }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No pending adjustments</p>
                            @endif
                        </div>

                        <!-- Expiring MTCs -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-clipboard-list me-1"></i>Expiring MTCs
                            </h6>
                            @if($workshopAlerts['expiring_mtcs']->count() > 0)
                                @foreach($workshopAlerts['expiring_mtcs'] as $mtc)
                                <div class="alert alert-primary alert-sm mb-2">
                                    <strong>MTC #{{ $mtc->mtc_number }}</strong><br>
                                    <small>{{ $mtc->item->name ?? 'N/A' }} - Expires {{ $mtc->expiry_date->format('M d, Y') }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No expiring MTCs</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $activity->type }}</span>
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->date ? $activity->date->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>
                                        @if($activity->status)
                                            <span class="badge bg-{{ $activity->status === 'open' ? 'warning' : ($activity->status === 'resolved' ? 'success' : 'info') }}">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->priority)
                                            <span class="badge bg-{{ $activity->priority === 'urgent' ? 'danger' : ($activity->priority === 'high' ? 'warning' : 'info') }}">
                                                {{ ucfirst($activity->priority) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent activities</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Craftsman Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Top Performing Craftsmen
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Craftsman</th>
                                    <th>Total Issues</th>
                                    <th>Resolved Issues</th>
                                    <th>Success Rate</th>
                                    <th>Total Adjustments</th>
                                    <th>Total Transfers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($craftsmanPerformance as $craftsman)
                                <tr>
                                    <td>
                                        <strong>{{ $craftsman->full_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $craftsman->total_issues }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $craftsman->resolved_issues }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $successRate = $craftsman->total_issues > 0 ? ($craftsman->resolved_issues / $craftsman->total_issues) * 100 : 0;
                                        @endphp
                                        <span class="badge bg-{{ $successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($successRate, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $craftsman->total_adjustments }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $craftsman->total_transfers }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No craftsman data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workshop Locations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Workshop Locations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($workshopLocations as $location)
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $location->name }}</h6>
                                    <p class="card-text">
                                        <span class="badge bg-primary">{{ $location->count }} activities</span>
                                    </p>
                                    <small class="text-muted">{{ $location->type }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <p class="text-muted text-center">No workshop locations found</p>
                        </div>
                        @endforelse
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
                            <a href="{{ route('reports.workshop.detailed') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.workshop.job-issues') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-tools me-1"></i>Job Issues Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.workshop.adjustments') }}" class="btn btn-info btn-block">
                                <i class="fas fa-balance-scale me-1"></i>Adjustments Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.workshop.transfers') }}" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle me-1"></i>Transfers Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.workshop.returns') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-arrow-left me-1"></i>Returns Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.workshop.mtcs') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-clipboard-list me-1"></i>MTCs Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
