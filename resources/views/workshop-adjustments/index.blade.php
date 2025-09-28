@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Workshop Adjustments</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-balance-scale me-2"></i>Workshop Adjustments Management
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
                            <h5 class="card-title mb-0">Workshop Adjustments</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('workshop-adjustments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create Adjustment
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
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="adjustmentTypeFilter">
                                <option value="">All Types</option>
                                <option value="material_used">Material Used</option>
                                <option value="scrap">Scrap</option>
                                <option value="defective">Defective</option>
                                <option value="correction">Correction</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search by reference or item...">
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

                    <!-- Workshop Adjustments Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Reference</th>
                                    <th>Item</th>
                                    <th>Craftsman</th>
                                    <th>Type</th>
                                    <th>Workshop Location</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Adjustment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workshopAdjustments as $adjustment)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="adjustment-checkbox" value="{{ $adjustment->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $adjustment->reference_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $adjustment->item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $adjustment->item->item_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $adjustment->craftsman ? $adjustment->craftsman->full_name : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $adjustment->adjustment_type)) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $adjustment->workshop_location }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $adjustment->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $adjustment->status_badge }}">
                                            {{ ucfirst($adjustment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $adjustment->adjustment_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('workshop-adjustments.show', $adjustment) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('workshop-adjustments.edit', $adjustment) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($adjustment->status === 'pending')
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="approveAdjustment({{ $adjustment->id }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="rejectAdjustment({{ $adjustment->id }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('workshop-adjustments.export-pdf', $adjustment) }}" 
                                               class="btn btn-sm btn-outline-info" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-balance-scale fa-3x mb-3"></i>
                                            <p>No workshop adjustments found.</p>
                                            <a href="{{ route('workshop-adjustments.create') }}" class="btn btn-primary">
                                                Create First Adjustment
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($workshopAdjustments->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $workshopAdjustments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Adjustment Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Workshop Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <input type="hidden" id="approveAdjustmentId">
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Approval Notes</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" 
                                  placeholder="Add approval notes (optional)..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Note:</strong> Approving this adjustment will update the item stock automatically.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Adjustment Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Workshop Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <input type="hidden" id="rejectAdjustmentId">
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectNotes" name="notes" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Adjustment</button>
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
    const adjustmentType = document.getElementById('adjustmentTypeFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    let url = new URL(window.location);
    url.searchParams.set('status', status);
    url.searchParams.set('adjustment_type', adjustmentType);
    url.searchParams.set('search', search);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('adjustmentTypeFilter').value = '';
    document.getElementById('searchFilter').value = '';
    applyFilters();
}

// Bulk selection functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.adjustment-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.adjustment-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.adjustment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.adjustment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Bulk status update
function bulkUpdateStatus() {
    const checkboxes = document.querySelectorAll('.adjustment-checkbox:checked');
    const status = document.getElementById('bulkStatus').value;
    
    if (checkboxes.length === 0) {
        alert('Please select at least one adjustment.');
        return;
    }
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    if (confirm(`Update ${checkboxes.length} adjustment(s) to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("workshop-adjustments.bulk-status-update") }}';
        
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
            input.name = 'workshop_adjustment_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Approve adjustment
function approveAdjustment(adjustmentId) {
    document.getElementById('approveAdjustmentId').value = adjustmentId;
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

// Reject adjustment
function rejectAdjustment(adjustmentId) {
    document.getElementById('rejectAdjustmentId').value = adjustmentId;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Handle approve form submission
document.getElementById('approveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const adjustmentId = document.getElementById('approveAdjustmentId').value;
    const notes = document.getElementById('approveNotes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('workshop-adjustments') }}/${adjustmentId}/approve`;
    
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
    
    const adjustmentId = document.getElementById('rejectAdjustmentId').value;
    const notes = document.getElementById('rejectNotes').value;
    
    if (!notes.trim()) {
        alert('Please provide a reason for rejection.');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('workshop-adjustments') }}/${adjustmentId}/reject`;
    
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
</script>
@endpush
