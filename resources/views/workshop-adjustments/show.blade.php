@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('workshop-adjustments.index') }}">Workshop Adjustments</a></li>
                        <li class="breadcrumb-item active">Adjustment Details</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-balance-scale me-2"></i>Workshop Adjustment - {{ $workshopAdjustment->reference_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Adjustment Details</h5>
                        <div>
                            <span class="badge bg-{{ $workshopAdjustment->status_badge }} fs-6">
                                {{ ucfirst($workshopAdjustment->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reference Number</label>
                                <p class="form-control-plaintext">{{ $workshopAdjustment->reference_number }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Adjustment Date</label>
                                <p class="form-control-plaintext">{{ $workshopAdjustment->adjustment_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $workshopAdjustment->item->name }}</strong><br>
                                    <small class="text-muted">{{ $workshopAdjustment->item->item_code }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Craftsman</label>
                                <p class="form-control-plaintext">
                                    {{ $workshopAdjustment->craftsman ? $workshopAdjustment->craftsman->full_name : 'Not assigned' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Adjustment Type</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $workshopAdjustment->adjustment_type)) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Workshop Location</label>
                                <p class="form-control-plaintext">{{ $workshopAdjustment->workshop_location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-secondary fs-6">{{ $workshopAdjustment->quantity }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approved By</label>
                                <p class="form-control-plaintext">
                                    {{ $workshopAdjustment->approvedBy ? $workshopAdjustment->approvedBy->name : 'Not approved yet' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $workshopAdjustment->reason }}
                        </div>
                    </div>

                    @if($workshopAdjustment->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $workshopAdjustment->notes }}
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
                        <a href="{{ route('workshop-adjustments.edit', $workshopAdjustment) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Adjustment
                        </a>

                        @if($workshopAdjustment->status === 'pending')
                        <button class="btn btn-success" onclick="approveAdjustment()">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                        <button class="btn btn-danger" onclick="rejectAdjustment()">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                        @endif

                        <a href="{{ route('workshop-adjustments.export-pdf', $workshopAdjustment) }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>

                        <a href="{{ route('workshop-adjustments.index') }}" class="btn btn-outline-secondary">
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
                        <strong>Name:</strong> {{ $workshopAdjustment->item->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Code:</strong> {{ $workshopAdjustment->item->item_code }}
                    </div>
                    <div class="mb-2">
                        <strong>Category:</strong> {{ $workshopAdjustment->item->category }}
                    </div>
                    <div class="mb-2">
                        <strong>Material:</strong> {{ $workshopAdjustment->item->material }}
                    </div>
                    @if($workshopAdjustment->item->gemstone)
                    <div class="mb-2">
                        <strong>Gemstone:</strong> {{ $workshopAdjustment->item->gemstone }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Current Stock:</strong> 
                        <span class="badge bg-{{ $workshopAdjustment->item->stock_status_color }}">
                            {{ $workshopAdjustment->item->current_stock }}
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
                    @if($workshopAdjustment->status === 'approved')
                        @if(in_array($workshopAdjustment->adjustment_type, ['material_used', 'scrap', 'defective']))
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Stock Decreased:</strong> {{ $workshopAdjustment->quantity }} units
                            </div>
                        @elseif($workshopAdjustment->adjustment_type === 'correction')
                            <div class="alert alert-success">
                                <i class="fas fa-plus me-1"></i>
                                <strong>Stock Increased:</strong> {{ $workshopAdjustment->quantity }} units
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>No Impact:</strong> Adjustment not yet approved
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
            <form action="{{ route('workshop-adjustments.approve', $workshopAdjustment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approveNotes" class="form-label">Approval Notes</label>
                        <textarea class="form-control" id="approveNotes" name="notes" rows="3" 
                                  placeholder="Add approval notes (optional)...">{{ $workshopAdjustment->notes }}</textarea>
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
            <form action="{{ route('workshop-adjustments.reject', $workshopAdjustment) }}" method="POST">
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
                    <button type="submit" class="btn btn-danger">Reject Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveAdjustment() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function rejectAdjustment() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush
