@extends('layouts.app')

@section('title', 'Job Issues Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tools me-2"></i>Job Issues Report
            </h1>
            <p class="text-muted mb-0">Detailed report of job issues and their resolution status</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'job_issues']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'job_issues']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'job_issues']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.workshop.job-issues') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="issue_type" class="form-label">Issue Type</label>
                        <select class="form-select" id="issue_type" name="issue_type">
                            <option value="">All Types</option>
                            <option value="defect" {{ request('issue_type') === 'defect' ? 'selected' : '' }}>Defect</option>
                            <option value="delay" {{ request('issue_type') === 'delay' ? 'selected' : '' }}>Delay</option>
                            <option value="quality" {{ request('issue_type') === 'quality' ? 'selected' : '' }}>Quality</option>
                            <option value="material" {{ request('issue_type') === 'material' ? 'selected' : '' }}>Material</option>
                            <option value="other" {{ request('issue_type') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="craftsman_id" class="form-label">Craftsman</label>
                        <select class="form-select" id="craftsman_id" name="craftsman_id">
                            <option value="">All Craftsmen</option>
                            @foreach($craftsmen as $craftsman)
                            <option value="{{ $craftsman->id }}" {{ request('craftsman_id') == $craftsman->id ? 'selected' : '' }}>
                                {{ $craftsman->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.workshop.job-issues') }}" class="btn btn-outline-secondary">
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobIssues->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                                Urgent Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobIssues->where('priority', 'urgent')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
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
                                Open Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobIssues->where('status', 'open')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Resolved Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobIssues->where('status', 'resolved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Issues Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Job Issues
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Job Number</th>
                            <th>Item</th>
                            <th>Craftsman</th>
                            <th>Issue Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Issue Date</th>
                            <th>Assigned To</th>
                            <th>Estimated Completion</th>
                            <th>Actual Completion</th>
                            <th>Resolution Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobIssues as $issue)
                        <tr>
                            <td>
                                <strong>{{ $issue->job_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $issue->item) }}" class="text-decoration-none">
                                    {{ $issue->item->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $issue->craftsman->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($issue->issue_type) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $issue->priority === 'urgent' ? 'danger' : ($issue->priority === 'high' ? 'warning' : 'info') }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $issue->status === 'open' ? 'warning' : ($issue->status === 'resolved' ? 'success' : 'info') }}">
                                    {{ ucfirst($issue->status) }}
                                </span>
                            </td>
                            <td>{{ $issue->issue_date->format('M d, Y') }}</td>
                            <td>{{ $issue->assignedTo->name ?? 'Unassigned' }}</td>
                            <td>
                                {{ $issue->estimated_completion ? $issue->estimated_completion->format('M d, Y') : 'Not set' }}
                            </td>
                            <td>
                                {{ $issue->actual_completion ? $issue->actual_completion->format('M d, Y') : 'Not completed' }}
                            </td>
                            <td>
                                @if($issue->resolution_time)
                                    <span class="badge bg-{{ $issue->resolution_time <= 7 ? 'success' : ($issue->resolution_time <= 14 ? 'warning' : 'danger') }}">
                                        {{ $issue->resolution_time }} days
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('job-issues.show', $issue) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('job-issues.edit', $issue) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No job issues found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($jobIssues->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $jobIssues->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
