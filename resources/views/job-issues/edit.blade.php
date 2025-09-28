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
                        <li class="breadcrumb-item active">Edit Job Issue</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Job Issue - {{ $jobIssue->job_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Job Issue Details</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('job-issues.update', $jobIssue) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_number" class="form-label">Job Number</label>
                                    <input type="text" class="form-control" value="{{ $jobIssue->job_number }}" readonly>
                                    <small class="form-text text-muted">Job number cannot be changed</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="open" {{ old('status', $jobIssue->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ old('status', $jobIssue->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ old('status', $jobIssue->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ old('status', $jobIssue->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                    <select class="form-select @error('item_id') is-invalid @enderror" 
                                            id="item_id" name="item_id" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ old('item_id', $jobIssue->item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="craftsman_id" class="form-label">Craftsman</label>
                                    <select class="form-select @error('craftsman_id') is-invalid @enderror" 
                                            id="craftsman_id" name="craftsman_id">
                                        <option value="">Select Craftsman (Optional)</option>
                                        @foreach($craftsmen as $craftsman)
                                        <option value="{{ $craftsman->id }}" 
                                                {{ old('craftsman_id', $jobIssue->craftsman_id) == $craftsman->id ? 'selected' : '' }}>
                                            {{ $craftsman->full_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('craftsman_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_type" class="form-label">Issue Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('issue_type') is-invalid @enderror" 
                                            id="issue_type" name="issue_type" required>
                                        <option value="">Select Issue Type</option>
                                        <option value="defect" {{ old('issue_type', $jobIssue->issue_type) == 'defect' ? 'selected' : '' }}>Defect</option>
                                        <option value="delay" {{ old('issue_type', $jobIssue->issue_type) == 'delay' ? 'selected' : '' }}>Delay</option>
                                        <option value="quality" {{ old('issue_type', $jobIssue->issue_type) == 'quality' ? 'selected' : '' }}>Quality</option>
                                        <option value="material" {{ old('issue_type', $jobIssue->issue_type) == 'material' ? 'selected' : '' }}>Material</option>
                                        <option value="other" {{ old('issue_type', $jobIssue->issue_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('issue_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority', $jobIssue->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $jobIssue->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $jobIssue->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority', $jobIssue->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('issue_date') is-invalid @enderror" 
                                           id="issue_date" name="issue_date" 
                                           value="{{ old('issue_date', $jobIssue->issue_date->format('Y-m-d')) }}" required>
                                    @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assigned To</label>
                                    <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                            id="assigned_to" name="assigned_to">
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" 
                                                {{ old('assigned_to', $jobIssue->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_completion" class="form-label">Estimated Completion</label>
                                    <input type="date" 
                                           class="form-control @error('estimated_completion') is-invalid @enderror" 
                                           id="estimated_completion" name="estimated_completion" 
                                           value="{{ old('estimated_completion', $jobIssue->estimated_completion?->format('Y-m-d')) }}">
                                    @error('estimated_completion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="actual_completion" class="form-label">Actual Completion</label>
                                    <input type="date" 
                                           class="form-control @error('actual_completion') is-invalid @enderror" 
                                           id="actual_completion" name="actual_completion" 
                                           value="{{ old('actual_completion', $jobIssue->actual_completion?->format('Y-m-d')) }}">
                                    @error('actual_completion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Describe the issue in detail..." required>{{ old('description', $jobIssue->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="resolution_notes" class="form-label">Resolution Notes</label>
                            <textarea class="form-control @error('resolution_notes') is-invalid @enderror" 
                                      id="resolution_notes" name="resolution_notes" rows="3" 
                                      placeholder="Add resolution notes...">{{ old('resolution_notes', $jobIssue->resolution_notes) }}</textarea>
                            @error('resolution_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('job-issues.show', $jobIssue) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('job-issues.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Job Issue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for estimated completion to issue date
    const issueDateInput = document.getElementById('issue_date');
    const estimatedCompletionInput = document.getElementById('estimated_completion');
    const actualCompletionInput = document.getElementById('actual_completion');
    
    issueDateInput.addEventListener('change', function() {
        estimatedCompletionInput.min = this.value;
        actualCompletionInput.min = this.value;
    });
    
    // Set initial minimum dates
    estimatedCompletionInput.min = issueDateInput.value;
    actualCompletionInput.min = issueDateInput.value;
});
</script>
@endpush
