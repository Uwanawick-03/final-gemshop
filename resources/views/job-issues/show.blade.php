@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('job-issues.index') }}">Job Issues</a></li>
                        <li class="breadcrumb-item active">Job Issue Details</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-tools me-2"></i>Job Issue - {{ $jobIssue->job_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Job Issue Details</h5>
                        <div>
                            <span class="badge bg-{{ $jobIssue->status_badge }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $jobIssue->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Job Number</label>
                                <p class="form-control-plaintext">{{ $jobIssue->job_number }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Type</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ ucfirst($jobIssue->issue_type) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $jobIssue->item->name }}</strong><br>
                                    <small class="text-muted">{{ $jobIssue->item->item_code }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Craftsman</label>
                                <p class="form-control-plaintext">
                                    {{ $jobIssue->craftsman ? $jobIssue->craftsman->full_name : 'Not assigned' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Priority</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $jobIssue->priority_badge }}">
                                        {{ ucfirst($jobIssue->priority) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Date</label>
                                <p class="form-control-plaintext">{{ $jobIssue->issue_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Assigned To</label>
                                <p class="form-control-plaintext">
                                    {{ $jobIssue->assignedTo ? $jobIssue->assignedTo->name : 'Unassigned' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estimated Completion</label>
                                <p class="form-control-plaintext">
                                    {{ $jobIssue->estimated_completion ? $jobIssue->estimated_completion->format('M d, Y') : 'Not set' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Actual Completion</label>
                                <p class="form-control-plaintext">
                                    {{ $jobIssue->actual_completion ? $jobIssue->actual_completion->format('M d, Y') : 'Not completed' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Resolved Date</label>
                                <p class="form-control-plaintext">
                                    {{ $jobIssue->resolved_date ? $jobIssue->resolved_date->format('M d, Y') : 'Not resolved' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $jobIssue->description }}
                        </div>
                    </div>

                    @if($jobIssue->resolution_notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Resolution Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $jobIssue->resolution_notes }}
                        </div>
                    </div>
                    @endif

                    @if($jobIssue->resolvedBy)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Resolved By</label>
                                <p class="form-control-plaintext">{{ $jobIssue->resolvedBy->name }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($jobIssue->resolution_time)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Resolution Time</label>
                                <p class="form-control-plaintext">{{ $jobIssue->resolution_time }} days</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('job-issues.edit', $jobIssue) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Job Issue
                        </a>

                        @if($jobIssue->status !== 'resolved' && $jobIssue->status !== 'closed')
                        <button class="btn btn-success" onclick="quickResolve()">
                            <i class="fas fa-check me-1"></i>Quick Resolve
                        </button>
                        @endif

                        <a href="{{ route('job-issues.export-pdf', $jobIssue) }}" class="btn btn-info">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>

                        <a href="{{ route('job-issues.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Update Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Status</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('job-issues.update-status', $jobIssue) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="open" {{ $jobIssue->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $jobIssue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $jobIssue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $jobIssue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="resolution_notes" class="form-label">Resolution Notes</label>
                            <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="3" 
                                      placeholder="Add resolution notes...">{{ $jobIssue->resolution_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sync me-1"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Item Details Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Item Details</h5>
                </div>

                <div class="card-body">
                    <div class="mb-2">
                        <strong>Name:</strong> {{ $jobIssue->item->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Code:</strong> {{ $jobIssue->item->item_code }}
                    </div>
                    <div class="mb-2">
                        <strong>Category:</strong> {{ $jobIssue->item->category }}
                    </div>
                    <div class="mb-2">
                        <strong>Material:</strong> {{ $jobIssue->item->material }}
                    </div>
                    @if($jobIssue->item->gemstone)
                    <div class="mb-2">
                        <strong>Gemstone:</strong> {{ $jobIssue->item->gemstone }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Current Stock:</strong> 
                        <span class="badge bg-{{ $jobIssue->item->stock_status_color }}">
                            {{ $jobIssue->item->current_stock }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Resolve Modal -->
<div class="modal fade" id="quickResolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Resolve Job Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('job-issues.update-status', $jobIssue) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status" value="resolved">
                    <div class="mb-3">
                        <label for="quick_resolution_notes" class="form-label">Resolution Notes</label>
                        <textarea class="form-control" id="quick_resolution_notes" name="resolution_notes" rows="3" 
                                  placeholder="Describe how the issue was resolved..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Resolve Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function quickResolve() {
    const modal = new bootstrap.Modal(document.getElementById('quickResolveModal'));
    modal.show();
}
</script>
@endpush
