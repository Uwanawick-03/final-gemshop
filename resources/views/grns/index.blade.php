@extends('layouts.app')

@section('title', 'Goods Receipt Notes (GRN)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-receipt me-2"></i>Goods Receipt Notes (GRN)</h2>
    <a href="{{ route('grns.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create New GRN
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('grns.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by GRN number or supplier...">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" 
                                {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="supplier_id">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" 
                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_from" 
                       value="{{ request('date_from') }}" 
                       placeholder="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_to" 
                       value="{{ request('date_to') }}" 
                       placeholder="To Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- Bulk Actions -->
        @if($grns->count() > 0)
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleSelectAll()">
                            <i class="fas fa-check-square me-1"></i>Select All
                        </button>
                        <span class="ms-2 text-muted" id="selectedCount">0 selected</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="bulkStatusUpdate('received')" disabled id="bulkReceivedBtn">
                            <i class="fas fa-check me-1"></i>Mark as Received
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="bulkStatusUpdate('verified')" disabled id="bulkVerifiedBtn">
                            <i class="fas fa-clipboard-check me-1"></i>Mark as Verified
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkStatusUpdate('completed')" disabled id="bulkCompletedBtn">
                            <i class="fas fa-check-double me-1"></i>Mark as Completed
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- GRNs Table -->
<div class="card">
    <div class="card-body">
        @if($grns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th>GRN Number</th>
                            <th>Supplier</th>
                            <th>GRN Date</th>
                            <th>Received Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Received By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grns as $grn)
                        <tr>
                            <td>
                                <input type="checkbox" class="grn-checkbox" value="{{ $grn->id }}" onchange="updateSelectedCount()">
                            </td>
                            <td>
                                <strong>{{ $grn->grn_number }}</strong>
                                @if($grn->purchase_order)
                                    <br><small class="text-muted">PO: {{ $grn->purchase_order->po_number }}</small>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $grn->supplier->company_name }}</strong>
                                    <br><small class="text-muted">{{ $grn->supplier->contact_person }}</small>
                                </div>
                            </td>
                            <td>{{ $grn->grn_date->format('M j, Y') }}</td>
                            <td>{{ $grn->received_date->format('M j, Y') }}</td>
                            <td>
                                <strong>{{ $grn->currency->symbol }}{{ number_format($grn->total_amount, 2) }}</strong>
                                @if($grn->exchange_rate != 1)
                                    <br><small class="text-muted">Rate: {{ $grn->exchange_rate }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $grn->status_color }}">
                                    {{ $grn->status_label }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    {{ $grn->user->name }}
                                    <br><small class="text-muted">{{ $grn->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('grns.show', $grn) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('grns.export-pdf', $grn) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Export PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @if(in_array($grn->status, ['draft', 'received', 'cancelled']))
                                        @if($grn->status === 'draft')
                                            <a href="{{ route('grns.edit', $grn) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('grns.destroy', $grn) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this GRN? This will revert stock levels.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete">
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
            <div class="d-flex justify-content-center mt-4">
                {{ $grns->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No GRNs found</h5>
                <p class="text-muted">Start by creating your first GRN to receive goods from suppliers.</p>
                <a href="{{ route('grns.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New GRN
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.grn-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.grn-checkbox:checked');
    const count = checkboxes.length;
    const selectedCount = document.getElementById('selectedCount');
    const bulkButtons = document.querySelectorAll('[id^="bulk"]');
    
    selectedCount.textContent = count + ' selected';
    
    // Enable/disable bulk action buttons
    bulkButtons.forEach(button => {
        button.disabled = count === 0;
    });
    
    // Update select all checkbox state
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const allCheckboxes = document.querySelectorAll('.grn-checkbox');
    selectAllCheckbox.checked = count === allCheckboxes.length && count > 0;
    selectAllCheckbox.indeterminate = count > 0 && count < allCheckboxes.length;
}

function bulkStatusUpdate(status) {
    const checkboxes = document.querySelectorAll('.grn-checkbox:checked');
    const grnIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (grnIds.length === 0) {
        alert('Please select at least one GRN.');
        return;
    }
    
    if (confirm(`Are you sure you want to update ${grnIds.length} GRN(s) status to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("grns.bulk-status-update") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        // Add GRN IDs
        grnIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'grn_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
