@extends('layouts.app')

@section('title', 'Stock Adjustments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-adjust me-2"></i>Stock Adjustments</h4>
        <div class="small text-muted">Manage inventory adjustments and corrections</div>
    </div>
    <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Stock Adjustment
    </a>
</div>

<!-- Status Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-warning status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Pending Adjustments</div>
                        <div class="status-number text-warning">{{ $statusCounts['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning bg-opacity-10">
                <a href="{{ route('stock-adjustments.index', ['status' => 'pending']) }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Pending
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-info status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Approved</div>
                        <div class="status-number text-info">{{ $statusCounts['approved'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info bg-opacity-10">
                <a href="{{ route('stock-adjustments.index', ['status' => 'approved']) }}" class="text-info text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Approved
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-success status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-check-double fa-2x text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Completed</div>
                        <div class="status-number text-success">{{ $statusCounts['completed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success bg-opacity-10">
                <a href="{{ route('stock-adjustments.index', ['status' => 'completed']) }}" class="text-success text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Completed
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-dark status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-chart-bar fa-2x text-dark"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Total Adjustments</div>
                        <div class="status-number text-dark">{{ $totalAdjustments ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-dark bg-opacity-10">
                <a href="{{ route('stock-adjustments.index') }}" class="text-dark text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View All
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Adjustment number, reason, notes...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('stock-adjustments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stock Adjustments Table -->
<div class="card">
    <div class="card-body">
        @if($stockAdjustments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Adjustment #</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockAdjustments as $adjustment)
                            <tr>
                                <td>
                                    <a href="{{ route('stock-adjustments.show', $adjustment) }}" class="text-decoration-none">
                                        <strong>{{ $adjustment->adjustment_number }}</strong>
                                    </a>
                                </td>
                                <td>{{ $adjustment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        N/A
                                    </span>
                                </td>
                                <td>N/A</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        N/A
                                    </span>
                                </td>
                                <td>N/A</td>
                                <td>Unknown</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('stock-adjustments.show', $adjustment) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(true) {{-- Always show edit/delete since status column doesn't exist --}}
                                            <a href="{{ route('stock-adjustments.edit', $adjustment) }}" 
                                               class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('stock-adjustments.destroy', $adjustment) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this adjustment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Showing {{ $stockAdjustments->firstItem() }} to {{ $stockAdjustments->lastItem() }} 
                    of {{ $stockAdjustments->total() }} results
                </div>
                {{ $stockAdjustments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-adjust fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No stock adjustments found</h5>
                <p class="text-muted">Create your first stock adjustment to get started.</p>
                <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create Stock Adjustment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.status-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.status-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.border-warning .status-icon {
    background-color: rgba(255, 193, 7, 0.1);
}

.border-info .status-icon {
    background-color: rgba(13, 202, 240, 0.1);
}

.border-success .status-icon {
    background-color: rgba(25, 135, 84, 0.1);
}

.border-dark .status-icon {
    background-color: rgba(33, 37, 41, 0.1);
}

.status-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .status-number {
        font-size: 1.5rem;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
    }
}
</style>
@endsection
