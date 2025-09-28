@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Finished Good Transfers</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-check-circle me-2"></i>Finished Good Transfers Management
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
                            <h5 class="card-title mb-0">Finished Good Transfers</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('finished-good-transfers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create Transfer
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
                                <option value="quality_check">Quality Check</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="qualityFilter">
                                <option value="">All Quality</option>
                                <option value="passed">Quality Passed</option>
                                <option value="failed">Quality Failed</option>
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
                                        <option value="quality_check">Quality Check</option>
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

                    <!-- Finished Good Transfers Table -->
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
                                    <th>From → To</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Quality Check</th>
                                    <th>Transfer Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($finishedGoodTransfers as $transfer)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="transfer-checkbox" value="{{ $transfer->id }}">
                                    </td>
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
                                    <td>
                                        {{ $transfer->craftsman ? $transfer->craftsman->full_name : 'N/A' }}
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $transfer->from_workshop }}</strong>
                                            <br>
                                            <small class="text-muted">→ {{ $transfer->to_location }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $transfer->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $transfer->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($transfer->quality_check_passed !== null)
                                            @if($transfer->quality_check_passed)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Passed
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Failed
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Not Checked</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transfer->transfer_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('finished-good-transfers.show', $transfer) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('finished-good-transfers.edit', $transfer) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($transfer->status === 'pending')
                                            <button class="btn btn-sm btn-outline-info" 
                                                    onclick="qualityCheck({{ $transfer->id }})" title="Quality Check">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @endif
                                            @if($transfer->status === 'quality_check' && $transfer->quality_check_passed)
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="completeTransfer({{ $transfer->id }})" title="Complete Transfer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            <a href="{{ route('finished-good-transfers.export-pdf', $transfer) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                                            <p>No finished good transfers found.</p>
                                            <a href="{{ route('finished-good-transfers.create') }}" class="btn btn-primary">
                                                Create First Transfer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($finishedGoodTransfers->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $finishedGoodTransfers->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quality Check Modal -->
<div class="modal fade" id="qualityCheckModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quality Check</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="qualityCheckForm">
                <div class="modal-body">
                    <input type="hidden" id="transferId">
                    <div class="mb-3">
                        <label class="form-label">Quality Check Result</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="quality_check_passed" id="qualityPassed" value="1">
                            <label class="form-check-label text-success" for="qualityPassed">
                                <i class="fas fa-check me-1"></i>Passed
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="quality_check_passed" id="qualityFailed" value="0">
                            <label class="form-check-label text-danger" for="qualityFailed">
                                <i class="fas fa-times me-1"></i>Failed
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="qualityNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="qualityNotes" name="notes" rows="3" 
                                  placeholder="Add quality check notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Quality Check</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Transfer Modal -->
<div class="modal fade" id="completeTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeTransferForm">
                <div class="modal-body">
                    <input type="hidden" id="completeTransferId">
                    <div class="mb-3">
                        <label for="receivedBy" class="form-label">Received By</label>
                        <select class="form-select" id="receivedBy" name="received_by">
                            <option value="">Select User (Optional)</option>
                            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="completeNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="completeNotes" name="notes" rows="3" 
                                  placeholder="Add completion notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Transfer</button>
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
    const quality = document.getElementById('qualityFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    let url = new URL(window.location);
    url.searchParams.set('status', status);
    url.searchParams.set('quality', quality);
    url.searchParams.set('search', search);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('qualityFilter').value = '';
    document.getElementById('searchFilter').value = '';
    applyFilters();
}

// Bulk selection functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.transfer-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.transfer-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.transfer-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.transfer-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Bulk status update
function bulkUpdateStatus() {
    const checkboxes = document.querySelectorAll('.transfer-checkbox:checked');
    const status = document.getElementById('bulkStatus').value;
    
    if (checkboxes.length === 0) {
        alert('Please select at least one transfer.');
        return;
    }
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    if (confirm(`Update ${checkboxes.length} transfer(s) to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("finished-good-transfers.bulk-status-update") }}';
        
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
            input.name = 'finished_good_transfer_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Quality check
function qualityCheck(transferId) {
    document.getElementById('transferId').value = transferId;
    const modal = new bootstrap.Modal(document.getElementById('qualityCheckModal'));
    modal.show();
}

// Complete transfer
function completeTransfer(transferId) {
    document.getElementById('completeTransferId').value = transferId;
    const modal = new bootstrap.Modal(document.getElementById('completeTransferModal'));
    modal.show();
}

// Handle quality check form submission
document.getElementById('qualityCheckForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const transferId = document.getElementById('transferId').value;
    const qualityCheckPassed = document.querySelector('input[name="quality_check_passed"]:checked').value;
    const notes = document.getElementById('qualityNotes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('finished-good-transfers') }}/${transferId}/quality-check`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add quality check passed
    const qualityInput = document.createElement('input');
    qualityInput.type = 'hidden';
    qualityInput.name = 'quality_check_passed';
    qualityInput.value = qualityCheckPassed;
    form.appendChild(qualityInput);
    
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

// Handle complete transfer form submission
document.getElementById('completeTransferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const transferId = document.getElementById('completeTransferId').value;
    const receivedBy = document.getElementById('receivedBy').value;
    const notes = document.getElementById('completeNotes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('finished-good-transfers') }}/${transferId}/complete-transfer`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add received by
    if (receivedBy) {
        const receivedByInput = document.createElement('input');
        receivedByInput.type = 'hidden';
        receivedByInput.name = 'received_by';
        receivedByInput.value = receivedBy;
        form.appendChild(receivedByInput);
    }
    
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
