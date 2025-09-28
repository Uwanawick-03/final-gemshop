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
                        <li class="breadcrumb-item active">Item Transfers</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-truck me-2"></i>Item Transfers Report
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
                    <form method="GET" action="{{ route('reports.inventory.transfers') }}">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <select class="form-select" id="reason" name="reason">
                                    <option value="">All Reasons</option>
                                    <option value="restock" {{ request('reason') == 'restock' ? 'selected' : '' }}>Restock</option>
                                    <option value="sale_transfer" {{ request('reason') == 'sale_transfer' ? 'selected' : '' }}>Sale Transfer</option>
                                    <option value="repair" {{ request('reason') == 'repair' ? 'selected' : '' }}>Repair</option>
                                    <option value="display" {{ request('reason') == 'display' ? 'selected' : '' }}>Display</option>
                                    <option value="storage" {{ request('reason') == 'storage' ? 'selected' : '' }}>Storage</option>
                                    <option value="damage" {{ request('reason') == 'damage' ? 'selected' : '' }}>Damage</option>
                                    <option value="other" {{ request('reason') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="item_id" class="form-label">Item</label>
                                <select class="form-select" id="item_id" name="item_id">
                                    <option value="">All Items</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                    @endforeach
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
                                <a href="{{ route('reports.inventory.transfers') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Item Transfers ({{ $transfers->total() }} total)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.inventory.export-pdf', array_merge(request()->query(), ['type' => 'transfers'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.inventory.export-excel', array_merge(request()->query(), ['type' => 'transfers'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.inventory.export-csv', array_merge(request()->query(), ['type' => 'transfers'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Transfer #</th>
                                    <th>Item</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Transferred By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                <tr>
                                    <td>
                                        <strong>{{ $transfer->reference_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $transfer->item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transfer->item->item_code }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $transfer->from_location }}</td>
                                    <td>{{ $transfer->to_location }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $transfer->quantity }}</span>
                                    </td>
                                    <td>{{ $transfer->transfer_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->reason_color }}">
                                            {{ $transfer->reason_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status_color }}">
                                            {{ $transfer->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $transfer->transferredBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('item-transfers.show', $transfer) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('item-transfers.edit', $transfer) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-truck fa-3x mb-3"></i>
                                            <p>No item transfers found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($transfers->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of {{ $transfers->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $transfers->appends(request()->query())->links() }}
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
                                <h4 class="text-primary">{{ $transfers->count() }}</h4>
                                <p class="text-muted mb-0">Total Transfers</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $transfers->where('status', 'pending')->count() }}</h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $transfers->where('status', 'in_transit')->count() }}</h4>
                                <p class="text-muted mb-0">In Transit</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $transfers->where('status', 'completed')->count() }}</h4>
                                <p class="text-muted mb-0">Completed</p>
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

document.getElementById('reason').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('item_id').addEventListener('change', function() {
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
