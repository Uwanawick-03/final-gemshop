@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Craftsman Returns</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-arrow-left me-2"></i>Craftsman Returns Management
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Craftsman Returns</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('craftsman-returns.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create Return
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="returnTypeFilter">
                                <option value="">All Types</option>
                                <option value="defective">Defective</option>
                                <option value="unused_material">Unused Material</option>
                                <option value="excess">Excess</option>
                                <option value="quality_issue">Quality Issue</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search by return number or item...">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Clear
                                </button>
                                <button class="btn btn-outline-primary" onclick="applyFilters()">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mb-3" id="bulkActions" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <span class="me-3" id="selectedCount">0</span> items selected
                                <div class="ms-auto">
                                    <select class="form-select d-inline-block w-auto me-2" id="bulkStatus">
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="completed">Completed</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary" onclick="bulkUpdateStatus()">
                                        Update Status
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Craftsman Returns Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Return Number</th>
                                    <th>Craftsman</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Return Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($craftsmanReturns as $return)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="return-checkbox" value="{{ $return->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $return->return_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $return->craftsman->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $return->craftsman->craftsman_code ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $return->item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $return->item->item_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $return->return_type)) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $return->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $return->status_badge }}">
                                            {{ ucfirst($return->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $return->return_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('craftsman-returns.show', $return) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('craftsman-returns.edit', $return) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($return->status === 'pending')
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="approveReturn({{ $return->id }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="rejectReturn({{ $return->id }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            @if($return->status === 'approved')
                                            <button class="btn btn-sm btn-outline-info" 
                                                    onclick="completeReturn({{ $return->id }})" title="Complete">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('craftsman-returns.export-pdf', $return) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-arrow-left fa-3x mb-3"></i>
                                            <p>No craftsman returns found.</p>
                                            <a href="{{ route('craftsman-returns.create') }}" class="btn btn-primary">
                                                Create First Return
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($craftsmanReturns->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $craftsmanReturns->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Return Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Craftsman Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <input type="hidden" id="approveReturnId">
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Approval Notes</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" 
                                  placeholder="Add approval notes (optional)..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Note:</strong> Approving this return will allow it to be completed and processed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Return Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Craftsman Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <input type="hidden" id="rejectReturnId">
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectNotes" name="notes" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Return Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Craftsman Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeForm">
                <div class="modal-body">
                    <input type="hidden" id="completeReturnId">
                    <div class="mb-3">
                        <label for="completeNotes" class="form-label">Completion Notes</label>
                        <textarea class="form-control" id="completeNotes" name="notes" rows="3" 
                                  placeholder="Add completion notes (optional)..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Note:</strong> Completing this return will update the item stock automatically.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Complete Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter functionality
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const returnType = document.getElementById('returnTypeFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    let url = new URL(window.location);
    url.searchParams.set('status', status);
    url.searchParams.set('return_type', returnType);
    url.searchParams.set('search', search);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('returnTypeFilter').value = '';
    document.getElementById('searchFilter').value = '';
    applyFilters();
}

// Bulk selection functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.return-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.return-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.return-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.return-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Bulk status update
function bulkUpdateStatus() {
    const checkboxes = document.querySelectorAll('.return-checkbox:checked');
    const status = document.getElementById('bulkStatus').value;
    
    if (checkboxes.length === 0) {
        alert('Please select at least one return.');
        return;
    }
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    if (confirm(`Update ${checkboxes.length} return(s) to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("craftsman-returns.bulk-status-update") }}';
        
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
        
        // Add selected IDs
        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'craftsman_return_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Approve return
function approveReturn(returnId) {
    document.getElementById('approveReturnId').value = returnId;
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

// Reject return
function rejectReturn(returnId) {
    document.getElementById('rejectReturnId').value = returnId;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Complete return
function completeReturn(returnId) {
    document.getElementById('completeReturnId').value = returnId;
    const modal = new bootstrap.Modal(document.getElementById('completeModal'));
    modal.show();
}

// Handle approve form submission
document.getElementById('approveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const returnId = document.getElementById('approveReturnId').value;
    const notes = document.getElementById('approveNotes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('craftsman-returns') }}/${returnId}/approve`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add notes
    if (notes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'notes';
        notesInput.value = notes;
        form.appendChild(notesInput);
    }
    
    document.body.appendChild(form);
    form.submit();
});

// Handle reject form submission
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const returnId = document.getElementById('rejectReturnId').value;
    const notes = document.getElementById('rejectNotes').value;
    
    if (!notes.trim()) {
        alert('Please provide a reason for rejection.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('craftsman-returns') }}/${returnId}/reject`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add notes
    const notesInput = document.createElement('input');
    notesInput.type = 'hidden';
    notesInput.name = 'notes';
    notesInput.value = notes;
    form.appendChild(notesInput);
    
    document.body.appendChild(form);
    form.submit();
});

// Handle complete form submission
document.getElementById('completeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const returnId = document.getElementById('completeReturnId').value;
    const notes = document.getElementById('completeNotes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('craftsman-returns') }}/${returnId}/complete`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add notes
    if (notes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'notes';
        notesInput.value = notes;
        form.appendChild(notesInput);
    }
    
    document.body.appendChild(form);
    form.submit();
});
</script>
@endpush
