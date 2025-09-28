@extends('layouts.app')

@section('title', 'Item Transfers')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Item Transfers</h1>
        <div>
            <a href="{{ route('item-transfers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Transfer
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Transfers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Transit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($inTransitTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($completedTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($overdueTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Cancelled</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($cancelledTransfers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Quantity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalQuantityTransferred, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('item-transfers.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Reference, item, location...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <select class="form-control" id="reason" name="reason">
                            <option value="">All Reasons</option>
                            @foreach($reasons as $reason)
                                <option value="{{ $reason }}" {{ request('reason') == $reason ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $reason)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="item_id" class="form-label">Item</label>
                        <select class="form-control" id="item_id" name="item_id">
                            <option value="">All Items</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="transferred_by" class="form-label">Transferred By</label>
                        <select class="form-control" id="transferred_by" name="transferred_by">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('transferred_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">Filter</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="from_location" class="form-label">From Location</label>
                        <input type="text" class="form-control" id="from_location" name="from_location" 
                               value="{{ request('from_location') }}" placeholder="From location...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="to_location" class="form-label">To Location</label>
                        <input type="text" class="form-control" id="to_location" name="to_location" 
                               value="{{ request('to_location') }}" placeholder="To location...">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Item Transfers</h6>
            <div>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkStatusUpdate('completed')">
                    <i class="fas fa-check"></i> Mark Selected as Completed
                </button>
                <button type="button" class="btn btn-sm btn-info" onclick="bulkStatusUpdate('in_transit')">
                    <i class="fas fa-truck"></i> Mark Selected as In Transit
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Reference</th>
                            <th>Item</th>
                            <th>From → To</th>
                            <th>Quantity</th>
                            <th>Transfer Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Transferred By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($itemTransfers as $transfer)
                            <tr>
                                <td>
                                    <input type="checkbox" class="transfer-checkbox" value="{{ $transfer->id }}">
                                </td>
                                <td>
                                    <a href="{{ route('item-transfers.show', $transfer) }}" class="text-decoration-none">
                                        {{ $transfer->reference_number }}
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $transfer->item ? $transfer->item->name : 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $transfer->item ? $transfer->item->item_code : 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="text-danger">{{ $transfer->from_location }}</span><br>
                                        <i class="fas fa-arrow-right"></i><br>
                                        <span class="text-success">{{ $transfer->to_location }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($transfer->quantity, 2) }}</td>
                                <td>
                                    <div>
                                        {{ $transfer->transfer_date ? $transfer->transfer_date->format('M d, Y') : 'N/A' }}
                                        @if($transfer->is_overdue)
                                            <br><small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                {{ $transfer->days_overdue }} days overdue
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td style="min-width: 120px;">
                                    @php
                                        $reason = $transfer->reason;
                                        $textColor = '#6c757d'; // default gray
                                        $textLabel = 'Unknown';
                                        
                                        if ($reason === 'restock') {
                                            $textColor = '#28a745'; // green
                                            $textLabel = 'Restock';
                                        } elseif ($reason === 'sale_transfer') {
                                            $textColor = '#17a2b8'; // blue
                                            $textLabel = 'Sale Transfer';
                                        } elseif ($reason === 'repair') {
                                            $textColor = '#ffc107'; // yellow
                                            $textLabel = 'Repair';
                                        } elseif ($reason === 'display') {
                                            $textColor = '#007bff'; // primary blue
                                            $textLabel = 'Display';
                                        } elseif ($reason === 'storage') {
                                            $textColor = '#6c757d'; // gray
                                            $textLabel = 'Storage';
                                        } elseif ($reason === 'damage') {
                                            $textColor = '#dc3545'; // red
                                            $textLabel = 'Damage';
                                        } elseif ($reason === 'other') {
                                            $textColor = '#343a40'; // dark
                                            $textLabel = 'Other';
                                        } else {
                                            $textColor = '#6c757d';
                                            $textLabel = $reason ?: 'Not specified';
                                        }
                                    @endphp
                                    <span style="color: {{ $textColor }}; font-weight: bold; font-size: 14px;">
                                        {{ $textLabel }}
                                    </span>
                                </td>
                                <td style="min-width: 120px;">
                                    @php
                                        $status = $transfer->status;
                                        $textColor = '#6c757d'; // default gray
                                        $textLabel = 'Unknown';
                                        
                                        if ($status === 'pending') {
                                            $textColor = '#ffc107'; // yellow
                                            $textLabel = 'Pending';
                                        } elseif ($status === 'in_transit') {
                                            $textColor = '#17a2b8'; // blue
                                            $textLabel = 'In Transit';
                                        } elseif ($status === 'completed') {
                                            $textColor = '#28a745'; // green
                                            $textLabel = 'Completed';
                                        } elseif ($status === 'cancelled') {
                                            $textColor = '#dc3545'; // red
                                            $textLabel = 'Cancelled';
                                        } else {
                                            $textColor = '#6c757d';
                                            $textLabel = $status ?: 'Not specified';
                                        }
                                    @endphp
                                    <span style="color: {{ $textColor }}; font-weight: bold; font-size: 14px;">
                                        {{ $textLabel }}
                                    </span>
                                </td>
                                <td>
                                    {{ $transfer->transferredBy ? $transfer->transferredBy->name : 'N/A' }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('item-transfers.show', $transfer) }}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($transfer->status !== 'completed')
                                            <a href="{{ route('item-transfers.edit', $transfer) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('item-transfers.export-pdf', $transfer) }}" 
                                           class="btn btn-sm btn-secondary" title="Export PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No item transfers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $itemTransfers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Bulk Status Update Modal -->
<div class="modal fade" id="bulkStatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Status Update</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="bulkStatusForm" method="POST" action="{{ route('item-transfers.bulk-status-update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="bulkStatus" name="status">
                    <div class="form-group">
                        <label for="received_by">Received By (Optional)</label>
                        <select class="form-control" id="received_by" name="received_by">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p id="selectedCount" class="text-muted"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.transfer-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkStatusUpdate(status) {
    const selectedCheckboxes = document.querySelectorAll('.transfer-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one transfer.');
        return;
    }
    
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    // Add hidden inputs for selected IDs
    const form = document.getElementById('bulkStatusForm');
    form.innerHTML = form.innerHTML.replace(/<input type="hidden" name="transfer_ids\[\]"[^>]*>/g, '');
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'transfer_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.getElementById('bulkStatus').value = status;
    document.getElementById('selectedCount').textContent = `${selectedIds.length} transfers selected`;
    
    $('#bulkStatusModal').modal('show');
}
</script>
@endpush
