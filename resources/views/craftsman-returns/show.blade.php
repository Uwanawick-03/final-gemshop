@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('craftsman-returns.index') }}">Craftsman Returns</a></li>
                        <li class="breadcrumb-item active">Return Details</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-arrow-left me-2"></i>Craftsman Return - {{ $craftsmanReturn->return_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Return Details</h5>
                        <div>
                            <span class="badge bg-{{ $craftsmanReturn->status_badge }} fs-6">
                                {{ ucfirst($craftsmanReturn->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Return Number</label>
                                <p class="form-control-plaintext">{{ $craftsmanReturn->return_number }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Return Date</label>
                                <p class="form-control-plaintext">{{ $craftsmanReturn->return_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Craftsman</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $craftsmanReturn->craftsman->full_name }}</strong><br>
                                    <small class="text-muted">{{ $craftsmanReturn->craftsman->craftsman_code ?? 'N/A' }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $craftsmanReturn->item->name }}</strong><br>
                                    <small class="text-muted">{{ $craftsmanReturn->item->item_code }}</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Return Type</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $craftsmanReturn->return_type)) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-secondary fs-6">{{ $craftsmanReturn->quantity }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Processed By</label>
                                <p class="form-control-plaintext">
                                    {{ $craftsmanReturn->processedBy ? $craftsmanReturn->processedBy->name : 'Not processed yet' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approved By</label>
                                <p class="form-control-plaintext">
                                    {{ $craftsmanReturn->approvedBy ? $craftsmanReturn->approvedBy->name : 'Not approved yet' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $craftsmanReturn->reason }}
                        </div>
                    </div>

                    @if($craftsmanReturn->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $craftsmanReturn->notes }}
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
                        <a href="{{ route('craftsman-returns.edit', $craftsmanReturn) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Return
                        </a>

                        @if($craftsmanReturn->status === 'pending')
                        <button class="btn btn-success" onclick="approveReturn()">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                        <button class="btn btn-danger" onclick="rejectReturn()">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                        @endif

                        @if($craftsmanReturn->status === 'approved')
                        <button class="btn btn-info" onclick="completeReturn()">
                            <i class="fas fa-check-double me-1"></i>Complete
                        </button>
                        @endif

                        <a href="{{ route('craftsman-returns.export-pdf', $craftsmanReturn) }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>

                        <a href="{{ route('craftsman-returns.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Item Details Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Item Details</h5>
                </div>

                <div class="card-body">
                    <div class="mb-2">
                        <strong>Name:</strong> {{ $craftsmanReturn->item->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Code:</strong> {{ $craftsmanReturn->item->item_code }}
                    </div>
                    <div class="mb-2">
                        <strong>Category:</strong> {{ $craftsmanReturn->item->category }}
                    </div>
                    <div class="mb-2">
                        <strong>Material:</strong> {{ $craftsmanReturn->item->material }}
                    </div>
                    @if($craftsmanReturn->item->gemstone)
                    <div class="mb-2">
                        <strong>Gemstone:</strong> {{ $craftsmanReturn->item->gemstone }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Current Stock:</strong> 
                        <span class="badge bg-{{ $craftsmanReturn->item->stock_status_color }}">
                            {{ $craftsmanReturn->item->current_stock }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Craftsman Details Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Craftsman Details</h5>
                </div>

                <div class="card-body">
                    <div class="mb-2">
                        <strong>Name:</strong> {{ $craftsmanReturn->craftsman->full_name }}
                    </div>
                    <div class="mb-2">
                        <strong>Code:</strong> {{ $craftsmanReturn->craftsman->craftsman_code ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Specialization:</strong> {{ $craftsmanReturn->craftsman->specialization ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong> {{ $craftsmanReturn->craftsman->phone ?? 'N/A' }}
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ $craftsmanReturn->craftsman->is_active ? 'success' : 'danger' }}">
                            {{ $craftsmanReturn->craftsman->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stock Impact Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Stock Impact</h5>
                </div>

                <div class="card-body">
                    @if($craftsmanReturn->status === 'completed')
                        @if(in_array($craftsmanReturn->return_type, ['defective', 'quality_issue']))
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Stock Decreased:</strong> {{ $craftsmanReturn->quantity }} units
                            </div>
                        @elseif(in_array($craftsmanReturn->return_type, ['unused_material', 'excess']))
                            <div class="alert alert-success">
                                <i class="fas fa-plus me-1"></i>
                                <strong>Stock Increased:</strong> {{ $craftsmanReturn->quantity }} units
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>No Impact:</strong> Return not yet completed
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
            <form action="{{ route('craftsman-returns.approve', $craftsmanReturn) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Approval Notes</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" 
                                  placeholder="Add approval notes (optional)...">{{ $craftsmanReturn->notes }}</textarea>
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
            <form action="{{ route('craftsman-returns.reject', $craftsmanReturn) }}" method="POST">
                @csrf
                <div class="modal-body">
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
            <form action="{{ route('craftsman-returns.complete', $craftsmanReturn) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="completeNotes" class="form-label">Completion Notes</label>
                        <textarea class="form-control" id="completeNotes" name="notes" rows="3" 
                                  placeholder="Add completion notes (optional)...">{{ $craftsmanReturn->notes }}</textarea>
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
function approveReturn() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function rejectReturn() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function completeReturn() {
    const modal = new bootstrap.Modal(document.getElementById('completeModal'));
    modal.show();
}
</script>
@endpush
