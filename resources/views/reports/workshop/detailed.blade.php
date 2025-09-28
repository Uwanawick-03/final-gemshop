@extends('layouts.app')

@section('title', 'Detailed Workshop Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-list me-2"></i>Detailed Workshop Report
            </h1>
            <p class="text-muted mb-0">Comprehensive workshop activities with advanced filtering</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'detailed']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'detailed']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'detailed']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.workshop.detailed') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Report Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="all" {{ $reportType === 'all' ? 'selected' : '' }}>All Activities</option>
                            <option value="job_issues" {{ $reportType === 'job_issues' ? 'selected' : '' }}>Job Issues</option>
                            <option value="adjustments" {{ $reportType === 'adjustments' ? 'selected' : '' }}>Workshop Adjustments</option>
                            <option value="transfers" {{ $reportType === 'transfers' ? 'selected' : '' }}>Finished Good Transfers</option>
                            <option value="returns" {{ $reportType === 'returns' ? 'selected' : '' }}>Craftsman Returns</option>
                            <option value="mtcs" {{ $reportType === 'mtcs' ? 'selected' : '' }}>MTCs</option>
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
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.workshop.detailed') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activities Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Workshop Activities
            </h6>
        </div>
        <div class="card-body">
            @if($reportType === 'all' || $reportType === 'job_issues')
            <!-- Job Issues Section -->
            <div class="mb-4">
                <h5 class="text-warning">
                    <i class="fas fa-tools me-2"></i>Job Issues
                </h5>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->where('type', 'JobIssue') as $issue)
                            <tr>
                                <td><strong>{{ $issue->job_number }}</strong></td>
                                <td>{{ $issue->item->name ?? 'N/A' }}</td>
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
                                    <a href="{{ route('job-issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No job issues found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($reportType === 'all' || $reportType === 'adjustments')
            <!-- Workshop Adjustments Section -->
            <div class="mb-4">
                <h5 class="text-info">
                    <i class="fas fa-balance-scale me-2"></i>Workshop Adjustments
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Reference Number</th>
                                <th>Item</th>
                                <th>Workshop Location</th>
                                <th>Adjustment Type</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Adjustment Date</th>
                                <th>Craftsman</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->where('type', 'WorkshopAdjustment') as $adjustment)
                            <tr>
                                <td><strong>{{ $adjustment->reference_number }}</strong></td>
                                <td>{{ $adjustment->item->name ?? 'N/A' }}</td>
                                <td>{{ $adjustment->workshop_location ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $adjustment->adjustment_type)) }}</span>
                                </td>
                                <td>{{ $adjustment->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $adjustment->status === 'pending' ? 'warning' : ($adjustment->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($adjustment->status) }}
                                    </span>
                                </td>
                                <td>{{ $adjustment->adjustment_date->format('M d, Y') }}</td>
                                <td>{{ $adjustment->craftsman->full_name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('workshop-adjustments.show', $adjustment) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No workshop adjustments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($reportType === 'all' || $reportType === 'transfers')
            <!-- Finished Good Transfers Section -->
            <div class="mb-4">
                <h5 class="text-success">
                    <i class="fas fa-check-circle me-2"></i>Finished Good Transfers
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Reference Number</th>
                                <th>Item</th>
                                <th>From Workshop</th>
                                <th>To Location</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Transfer Date</th>
                                <th>Quality Check</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->where('type', 'FinishedGoodTransfer') as $transfer)
                            <tr>
                                <td><strong>{{ $transfer->reference_number }}</strong></td>
                                <td>{{ $transfer->item->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->from_workshop ?? 'N/A' }}</td>
                                <td>{{ $transfer->to_location ?? 'N/A' }}</td>
                                <td>{{ $transfer->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $transfer->status === 'pending' ? 'warning' : ($transfer->status === 'completed' ? 'success' : 'info') }}">
                                        {{ ucfirst($transfer->status) }}
                                    </span>
                                </td>
                                <td>{{ $transfer->transfer_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transfer->quality_check_passed ? 'success' : 'danger' }}">
                                        {{ $transfer->quality_check_passed ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('finished-good-transfers.show', $transfer) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No finished good transfers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($reportType === 'all' || $reportType === 'returns')
            <!-- Craftsman Returns Section -->
            <div class="mb-4">
                <h5 class="text-danger">
                    <i class="fas fa-arrow-left me-2"></i>Craftsman Returns
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Return Number</th>
                                <th>Item</th>
                                <th>Craftsman</th>
                                <th>Return Type</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Return Date</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->where('type', 'CraftsmanReturn') as $return)
                            <tr>
                                <td><strong>{{ $return->return_number }}</strong></td>
                                <td>{{ $return->item->name ?? 'N/A' }}</td>
                                <td>{{ $return->craftsman->full_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $return->return_type)) }}</span>
                                </td>
                                <td>{{ $return->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'completed' ? 'success' : 'info') }}">
                                        {{ ucfirst($return->status) }}
                                    </span>
                                </td>
                                <td>{{ $return->return_date->format('M d, Y') }}</td>
                                <td>{{ Str::limit($return->reason, 30) }}</td>
                                <td>
                                    <a href="{{ route('craftsman-returns.show', $return) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No craftsman returns found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($reportType === 'all' || $reportType === 'mtcs')
            <!-- MTCs Section -->
            <div class="mb-4">
                <h5 class="text-primary">
                    <i class="fas fa-clipboard-list me-2"></i>Material Transfer Certificates (MTCs)
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>MTC Number</th>
                                <th>Item</th>
                                <th>Customer</th>
                                <th>Sales Assistant</th>
                                <th>Status</th>
                                <th>Issue Date</th>
                                <th>Expiry Date</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->where('type', 'Mtc') as $mtc)
                            <tr>
                                <td><strong>{{ $mtc->mtc_number }}</strong></td>
                                <td>{{ $mtc->item->name ?? 'N/A' }}</td>
                                <td>{{ $mtc->customer->full_name ?? 'N/A' }}</td>
                                <td>{{ $mtc->salesAssistant->full_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $mtc->status === 'active' ? 'success' : ($mtc->status === 'expired' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($mtc->status) }}
                                    </span>
                                </td>
                                <td>{{ $mtc->issue_date->format('M d, Y') }}</td>
                                <td>
                                    {{ $mtc->expiry_date->format('M d, Y') }}
                                    @if($mtc->is_expiring_soon)
                                        <span class="badge bg-warning ms-1">Expiring Soon</span>
                                    @endif
                                </td>
                                <td>${{ number_format($mtc->purchase_price, 2) }}</td>
                                <td>${{ number_format($mtc->selling_price, 2) }}</td>
                                <td>
                                    <a href="{{ route('mtcs.show', $mtc) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No MTCs found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $activities->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
