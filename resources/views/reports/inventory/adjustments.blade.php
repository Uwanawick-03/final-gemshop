@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.inventory.index') }}">Inventory Report</a></li>
                        <li class="breadcrumb-item active">Stock Adjustments</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-balance-scale me-2"></i>Stock Adjustments Report
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.inventory.adjustments') }}">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">All Types</option>
                                    <option value="increase" {{ request('type') == 'increase' ? 'selected' : '' }}>Increase</option>
                                    <option value="decrease" {{ request('type') == 'decrease' ? 'selected' : '' }}>Decrease</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a href="{{ route('reports.inventory.adjustments') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Stock Adjustments ({{ $adjustments->total() }} total)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.inventory.export-pdf', array_merge(request()->query(), ['type' => 'adjustments'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.inventory.export-excel', array_merge(request()->query(), ['type' => 'adjustments'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.inventory.export-csv', array_merge(request()->query(), ['type' => 'adjustments'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Adjustment #</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Items</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Approved By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adjustments as $adjustment)
                                <tr>
                                    <td>
                                        <strong>{{ $adjustment->adjustment_number }}</strong>
                                    </td>
                                    <td>{{ isset($adjustment->adjustment_date) ? $adjustment->adjustment_date->format('M d, Y') : $adjustment->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if(isset($adjustment->type))
                                            <span class="badge bg-{{ $adjustment->type == 'increase' ? 'success' : 'danger' }}">
                                                {{ ucfirst($adjustment->type) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $adjustment->total_items }}</span>
                                    </td>
                                    <td>{{ isset($adjustment->reason) ? Str::limit($adjustment->reason, 30) : 'N/A' }}</td>
                                    <td>
                                        @if(isset($adjustment->status))
                                            <span class="badge bg-{{ $adjustment->status_color }}">
                                                {{ $adjustment->status_label }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $adjustment->createdBy->name ?? 'N/A' }}</td>
                                    <td>{{ $adjustment->approvedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('stock-adjustments.show', $adjustment) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('stock-adjustments.edit', $adjustment) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-balance-scale fa-3x mb-3"></i>
                                            <p>No stock adjustments found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($adjustments->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $adjustments->firstItem() }} to {{ $adjustments->lastItem() }} of {{ $adjustments->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $adjustments->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $adjustments->count() }}</h4>
                                <p class="text-muted mb-0">Total Adjustments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $adjustments->where('status', 'pending')->count() }}</h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $adjustments->where('status', 'approved')->count() }}</h4>
                                <p class="text-muted mb-0">Approved</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $adjustments->where('status', 'rejected')->count() }}</h4>
                                <p class="text-muted mb-0">Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form on filter change
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('type').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('start_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('end_date').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
