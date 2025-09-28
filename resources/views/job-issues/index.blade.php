@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Job Issues</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-tools me-2"></i>Job Issues Management
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
                            <h5 class="card-title mb-0">Job Issues</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('job-issues.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create Job Issue
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
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priorityFilter">
                                <option value="">All Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="issueTypeFilter">
                                <option value="">All Types</option>
                                <option value="defect">Defect</option>
                                <option value="delay">Delay</option>
                                <option value="quality">Quality</option>
                                <option value="material">Material</option>
                                <option value="other">Other</option>
                            </select>
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
                                        <option value="open">Open</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="closed">Closed</option>
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

                    <!-- Job Issues Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Job Number</th>
                                    <th>Item</th>
                                    <th>Craftsman</th>
                                    <th>Issue Type</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Issue Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobIssues as $jobIssue)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="job-issue-checkbox" value="{{ $jobIssue->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $jobIssue->job_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $jobIssue->item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $jobIssue->item->item_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $jobIssue->craftsman ? $jobIssue->craftsman->full_name : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($jobIssue->issue_type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $jobIssue->priority_badge }}">
                                            {{ ucfirst($jobIssue->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $jobIssue->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $jobIssue->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $jobIssue->assignedTo ? $jobIssue->assignedTo->name : 'Unassigned' }}
                                    </td>
                                    <td>
                                        {{ $jobIssue->issue_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('job-issues.show', $jobIssue) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('job-issues.edit', $jobIssue) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="quickStatusUpdate({{ $jobIssue->id }})" title="Update Status">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <a href="{{ route('job-issues.export-pdf', $jobIssue) }}" 
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
                                            <i class="fas fa-tools fa-3x mb-3"></i>
                                            <p>No job issues found.</p>
                                            <a href="{{ route('job-issues.create') }}" class="btn btn-primary">
                                                Create First Job Issue
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($jobIssues->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $jobIssues->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Status Update Modal -->
<div class="modal fade" id="quickStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Job Issue Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickStatusForm">
                <div class="modal-body">
                    <input type="hidden" id="jobIssueId">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="resolution_notes" class="form-label">Resolution Notes</label>
                        <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="3"></textarea>
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

@endsection

@push('scripts')
<script>
// Filter functionality
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const priority = document.getElementById('priorityFilter').value;
    const issueType = document.getElementById('issueTypeFilter').value;
    
    let url = new URL(window.location);
    url.searchParams.set('status', status);
    url.searchParams.set('priority', priority);
    url.searchParams.set('issue_type', issueType);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    document.getElementById('issueTypeFilter').value = '';
    applyFilters();
}

// Bulk selection functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.job-issue-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.job-issue-checkbox:checked');
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
    const checkboxes = document.querySelectorAll('.job-issue-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.job-issue-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Bulk status update
function bulkUpdateStatus() {
    const checkboxes = document.querySelectorAll('.job-issue-checkbox:checked');
    const status = document.getElementById('bulkStatus').value;
    
    if (checkboxes.length === 0) {
        alert('Please select at least one job issue.');
        return;
    }
    
    if (!status) {
        alert('Please select a status.');
        return;
    }
    
    if (confirm(`Update ${checkboxes.length} job issue(s) to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("job-issues.bulk-status-update") }}';
        
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
            input.name = 'job_issue_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Quick status update
function quickStatusUpdate(jobIssueId) {
    document.getElementById('jobIssueId').value = jobIssueId;
    const modal = new bootstrap.Modal(document.getElementById('quickStatusModal'));
    modal.show();
}

// Handle quick status form submission
document.getElementById('quickStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const jobIssueId = document.getElementById('jobIssueId').value;
    const status = document.getElementById('status').value;
    const resolutionNotes = document.getElementById('resolution_notes').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('job-issues') }}/${jobIssueId}/update-status`;
    
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
    
    // Add resolution notes
    if (resolutionNotes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'resolution_notes';
        notesInput.value = resolutionNotes;
        form.appendChild(notesInput);
    }
    
    document.body.appendChild(form);
    form.submit();
});
</script>
@endpush
