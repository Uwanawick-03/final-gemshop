@extends('layouts.app')

@section('title', 'Supplier Returns')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Supplier Returns</h1>
                    <p class="text-muted">Manage supplier returns and refunds</p>
                </div>
                <div>
                    <a href="{{ route('supplier-returns.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create Return
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Returns</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalReturns) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalValueAmount">{{ number_format($totalValueInLKR, 2) }}</div>
                            <small class="text-muted">Total Value (<span id="totalValueCurrency">LKR</span>)</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending ({{ $pendingCount }})</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">LKR {{ number_format($pendingAmount, 2) }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">LKR {{ number_format($completedAmount, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('supplier-returns.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Return number, supplier...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <select class="form-select" id="reason" name="reason">
                            <option value="">All Reasons</option>
                            @foreach($reasons as $key => $label)
                                <option value="{{ $key }}" {{ request('reason') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Supplier Returns Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Supplier Returns</h6>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="bulkStatusUpdate()">
                    <i class="fas fa-edit me-1"></i> Bulk Update
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($supplierReturns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="supplierReturnsTable">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>Return #</th>
                                <th>Supplier</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplierReturns as $return)
                                <tr data-return-id="{{ $return->id }}">
                                    <td>
                                        <input type="checkbox" class="return-checkbox" value="{{ $return->id }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('supplier-returns.show', $return) }}" class="text-decoration-none">
                                            {{ $return->return_number }}
                                        </a>
                                    </td>
                                    <td>{{ $return->supplier->company_name }}</td>
                                    <td>{{ $return->return_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="fw-bold display-amount">{{ number_format($return->total_amount, 2) }}</span>
                                        <small class="text-muted d-block display-currency">{{ $return->currency->code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $return->status_color }}">
                                            {{ $return->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $return->reason_color }}">
                                            {{ $return->reason_label }}
                                        </span>
                                    </td>
                                    <td>{{ $return->createdBy?->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('supplier-returns.show', $return) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($return->status === 'pending')
                                                <a href="{{ route('supplier-returns.edit', $return) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="More Actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('supplier-returns.export-pdf', $return) }}">
                                                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                                                        </a>
                                                    </li>
                                                    @if($return->status === 'pending')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('supplier-returns.destroy', $return) }}" method="POST" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this supplier return?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $supplierReturns->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No supplier returns found</h5>
                    <p class="text-muted">Get started by creating your first supplier return.</p>
                    <a href="{{ route('supplier-returns.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create Return
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Status Update Modal -->
<div class="modal fade" id="bulkStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Status Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkStatusForm" method="POST" action="{{ route('supplier-returns.bulk-status-update') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_status" class="form-label">New Status</label>
                        <select class="form-select" id="bulk_status" name="status" required>
                            <option value="">Select Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted">
                            <span id="selectedCount">0</span> return(s) selected
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Currency conversion functionality
function updateCurrencyDisplay(newCurrency) {
    console.log('Updating currency display to:', newCurrency);
    
    // Update all amount displays
    const amountElements = document.querySelectorAll('.display-amount');
    const currencyElements = document.querySelectorAll('.display-currency');
    
    amountElements.forEach((element, index) => {
        const row = element.closest('tr');
        const returnId = row.dataset.returnId;
        const originalAmount = parseFloat(element.textContent.replace(/,/g, ''));
        const originalCurrency = currencyElements[index].textContent.trim();
        
        if (originalCurrency !== newCurrency) {
            // Convert amount
            fetch(`{{ route('supplier-returns.exchange-rates') }}`)
                .then(response => response.json())
                .then(data => {
                    const fromCurrency = data.currencies.find(c => c.code === originalCurrency);
                    const toCurrency = data.currencies.find(c => c.code === newCurrency);
                    
                    if (fromCurrency && toCurrency) {
                        const convertedAmount = (originalAmount * fromCurrency.exchange_rate) / toCurrency.exchange_rate;
                        element.textContent = convertedAmount.toFixed(2);
                        currencyElements[index].textContent = newCurrency;
                    }
                })
                .catch(error => console.error('Error converting currency:', error));
        }
    });
}

function updateTotalValue(newCurrency) {
    console.log('Updating total value to currency:', newCurrency);
    
    if (newCurrency === 'LKR') {
        // Server already calculated in LKR, no need to convert
        return;
    }
    
    fetch(`{{ route('supplier-returns.exchange-rates') }}`)
        .then(response => response.json())
        .then(data => {
            const lkrCurrency = data.currencies.find(c => c.code === 'LKR');
            const newCurrencyData = data.currencies.find(c => c.code === newCurrency);
            
            if (lkrCurrency && newCurrencyData) {
                const currentTotal = parseFloat(document.getElementById('totalValueAmount').textContent.replace(/,/g, ''));
                const convertedTotal = (currentTotal * lkrCurrency.exchange_rate) / newCurrencyData.exchange_rate;
                
                document.getElementById('totalValueAmount').textContent = convertedTotal.toFixed(2);
                document.getElementById('totalValueCurrency').textContent = newCurrency;
            }
        })
        .catch(error => console.error('Error converting total value:', error));
}

// Initialize total value with current display currency
const currentDisplayCurrency = document.getElementById('currentDisplayCurrency');
if (currentDisplayCurrency) {
    const initialCurrency = currentDisplayCurrency.textContent.trim();
    console.log('Initial currency:', initialCurrency);
    // Only update if currency is not LKR (since server already calculated in LKR)
    if (initialCurrency !== 'LKR') {
        updateTotalValue(initialCurrency);
    }
} else {
    console.log('currentDisplayCurrency element not found');
    // Server already calculated in LKR, no need to update
}

// Listen for currency display changes
const currencyDisplayDropdown = document.getElementById('currentDisplayCurrency');
if (currencyDisplayDropdown) {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                const newCurrency = mutation.target.textContent.trim();
                updateCurrencyDisplay(newCurrency);
                // Only update total value if currency is not LKR (server calculated in LKR)
                if (newCurrency !== 'LKR') {
                    updateTotalValue(newCurrency);
                }
            }
        });
    });
    
    observer.observe(currencyDisplayDropdown, {
        childList: true,
        characterData: true,
        subtree: true
    });
}

// Bulk operations
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.return-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const selectedCheckboxes = document.querySelectorAll('.return-checkbox:checked');
    const count = selectedCheckboxes.length;
    document.getElementById('selectedCount').textContent = count;
    
    // Update bulk update button state
    const bulkButton = document.querySelector('button[onclick="bulkStatusUpdate()"]');
    if (bulkButton) {
        bulkButton.disabled = count === 0;
    }
}

function bulkStatusUpdate() {
    const selectedCheckboxes = document.querySelectorAll('.return-checkbox:checked');
    const count = selectedCheckboxes.length;
    
    if (count === 0) {
        alert('Please select at least one supplier return.');
        return;
    }
    
    // Add selected return IDs to form
    const form = document.getElementById('bulkStatusForm');
    const existingInputs = form.querySelectorAll('input[name="supplier_return_ids[]"]');
    existingInputs.forEach(input => input.remove());
    
    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'supplier_return_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bulkStatusModal'));
    modal.show();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.return-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    updateSelectedCount();
});
</script>
@endsection
