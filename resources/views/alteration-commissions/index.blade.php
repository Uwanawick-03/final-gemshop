@extends('layouts.app')

@section('title', 'Alteration Commissions')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Alteration Commissions</h1>
        <div>
            <a href="{{ route('alteration-commissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Commission
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Commissions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCommissions) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingCommissions) }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($inProgressCommissions) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($completedCommissions) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($overdueCommissions) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($cancelledCommissions) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Value (LKR)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalValueInLKR, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Paid Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($paidAmount, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('alteration-commissions.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Commission number, customer, item...">
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
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">All Payment Statuses</option>
                            @foreach($paymentStatuses as $paymentStatus)
                                <option value="{{ $paymentStatus }}" {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}>
                                    {{ ucfirst($paymentStatus) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="alteration_type" class="form-label">Alteration Type</label>
                        <select class="form-control" id="alteration_type" name="alteration_type">
                            <option value="">All Types</option>
                            @foreach($alterationTypes as $type)
                                <option value="{{ $type }}" {{ request('alteration_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->first_name }} {{ $customer->last_name }}
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
                        <label for="craftsman_id" class="form-label">Craftsman</label>
                        <select class="form-control" id="craftsman_id" name="craftsman_id">
                            <option value="">All Craftsmen</option>
                            @foreach($craftsmen as $craftsman)
                                <option value="{{ $craftsman->id }}" {{ request('craftsman_id') == $craftsman->id ? 'selected' : '' }}>
                                    {{ $craftsman->first_name }} {{ $craftsman->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sales_assistant_id" class="form-label">Sales Assistant</label>
                        <select class="form-control" id="sales_assistant_id" name="sales_assistant_id">
                            <option value="">All Sales Assistants</option>
                            @foreach($salesAssistants as $salesAssistant)
                                <option value="{{ $salesAssistant->id }}" {{ request('sales_assistant_id') == $salesAssistant->id ? 'selected' : '' }}>
                                    {{ $salesAssistant->first_name }} {{ $salesAssistant->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Alteration Commissions</h6>
            <div>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkStatusUpdate('completed')">
                    <i class="fas fa-check"></i> Mark Selected as Completed
                </button>
                <button type="button" class="btn btn-sm btn-info" onclick="bulkStatusUpdate('in_progress')">
                    <i class="fas fa-cog"></i> Mark Selected as In Progress
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
                            <th>Commission #</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alterationCommissions as $commission)
                            <tr>
                                <td>
                                    <input type="checkbox" class="commission-checkbox" value="{{ $commission->id }}">
                                </td>
                                <td>
                                    <a href="{{ route('alteration-commissions.show', $commission) }}" class="text-decoration-none">
                                        {{ $commission->commission_number }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $commission->commission_date->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $commission->customer->first_name }} {{ $commission->customer->last_name }}</strong><br>
                                        <small class="text-muted">{{ $commission->customer->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($commission->item)
                                        <div>
                                            <strong>{{ $commission->item->name }}</strong><br>
                                            <small class="text-muted">{{ $commission->item->item_code }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">No item</span>
                                    @endif
                                </td>
                                <td style="min-width: 120px;">
                                    @php
                                        $type = $commission->alteration_type;
                                        $textColor = '#6c757d'; // default gray
                                        $textLabel = 'Unknown';
                                        
                                        if ($type === 'resizing') {
                                            $textColor = '#007bff'; // primary blue
                                            $textLabel = 'Resizing';
                                        } elseif ($type === 'repair') {
                                            $textColor = '#ffc107'; // yellow
                                            $textLabel = 'Repair';
                                        } elseif ($type === 'engraving') {
                                            $textColor = '#17a2b8'; // info blue
                                            $textLabel = 'Engraving';
                                        } elseif ($type === 'cleaning') {
                                            $textColor = '#6c757d'; // gray
                                            $textLabel = 'Cleaning';
                                        } elseif ($type === 'customization') {
                                            $textColor = '#28a745'; // green
                                            $textLabel = 'Customization';
                                        } elseif ($type === 'restoration') {
                                            $textColor = '#343a40'; // dark
                                            $textLabel = 'Restoration';
                                        } elseif ($type === 'setting_change') {
                                            $textColor = '#dc3545'; // red
                                            $textLabel = 'Setting Change';
                                        } elseif ($type === 'other') {
                                            $textColor = '#f8f9fa'; // light
                                            $textLabel = 'Other';
                                        } else {
                                            $textColor = '#6c757d';
                                            $textLabel = $type ?: 'Not specified';
                                        }
                                    @endphp
                                    <span style="color: {{ $textColor }}; font-weight: bold; font-size: 14px;">
                                        {{ $textLabel }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ number_format($commission->commission_amount, 2) }}</strong><br>
                                        <small class="text-muted">{{ $commission->currency->code }}</small>
                                    </div>
                                </td>
                                <td style="min-width: 120px;">
                                    @php
                                        $status = $commission->status;
                                        $textColor = '#6c757d'; // default gray
                                        $textLabel = 'Unknown';
                                        
                                        if ($status === 'pending') {
                                            $textColor = '#ffc107'; // yellow
                                            $textLabel = 'Pending';
                                        } elseif ($status === 'in_progress') {
                                            $textColor = '#17a2b8'; // blue
                                            $textLabel = 'In Progress';
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
                                    @if($commission->is_overdue)
                                        <br><small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Overdue
                                        </small>
                                    @endif
                                </td>
                                <td style="min-width: 120px;">
                                    @php
                                        $paymentStatus = $commission->payment_status;
                                        $textColor = '#6c757d'; // default gray
                                        $textLabel = 'Unknown';
                                        
                                        if ($paymentStatus === 'unpaid') {
                                            $textColor = '#dc3545'; // red
                                            $textLabel = 'Unpaid';
                                        } elseif ($paymentStatus === 'partial') {
                                            $textColor = '#ffc107'; // yellow
                                            $textLabel = 'Partial';
                                        } elseif ($paymentStatus === 'paid') {
                                            $textColor = '#28a745'; // green
                                            $textLabel = 'Paid';
                                        } else {
                                            $textColor = '#6c757d';
                                            $textLabel = $paymentStatus ?: 'Not specified';
                                        }
                                    @endphp
                                    <span style="color: {{ $textColor }}; font-weight: bold; font-size: 14px;">
                                        {{ $textLabel }}
                                    </span>
                                    @if($commission->paid_amount > 0)
                                        <br><small class="text-muted">
                                            Paid: {{ number_format($commission->paid_amount, 2) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $commission->payment_status_color }}" 
                                             role="progressbar" 
                                             style="width: {{ $commission->progress_percentage }}%"
                                             aria-valuenow="{{ $commission->progress_percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $commission->progress_percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('alteration-commissions.show', $commission) }}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($commission->status !== 'completed')
                                            <a href="{{ route('alteration-commissions.edit', $commission) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('alteration-commissions.export-pdf', $commission) }}" 
                                           class="btn btn-sm btn-secondary" title="Export PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No alteration commissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $alterationCommissions->links() }}
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
            <form id="bulkStatusForm" method="POST" action="{{ route('alteration-commissions.bulk-status-update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="bulkStatus" name="status">
                    <div class="form-group">
                        <label for="completion_date">Completion Date (Optional)</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date">
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
    const checkboxes = document.querySelectorAll('.commission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkStatusUpdate(status) {
    const selectedCheckboxes = document.querySelectorAll('.commission-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one commission.');
        return;
    }
    
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    // Add hidden inputs for selected IDs
    const form = document.getElementById('bulkStatusForm');
    form.innerHTML = form.innerHTML.replace(/<input type="hidden" name="commission_ids\[\]"[^>]*>/g, '');
    
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'commission_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.getElementById('bulkStatus').value = status;
    document.getElementById('selectedCount').textContent = `${selectedIds.length} commissions selected`;
    
    $('#bulkStatusModal').modal('show');
}
</script>
@endpush
