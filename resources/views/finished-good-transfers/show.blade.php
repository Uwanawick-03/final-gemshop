@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('finished-good-transfers.index') }}">Finished Good Transfers</a></li>
                        <li class="breadcrumb-item active">Transfer Details</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-check-circle me-2"></i>Finished Good Transfer - {{ $finishedGoodTransfer->reference_number }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Transfer Details</h5>
                        <div>
                            <span class="badge bg-{{ $finishedGoodTransfer->status_badge }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $finishedGoodTransfer->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reference Number</label>
                                <p class="form-control-plaintext">{{ $finishedGoodTransfer->reference_number }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Transfer Date</label>
                                <p class="form-control-plaintext">{{ $finishedGoodTransfer->transfer_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Item</label>
                                <p class="form-control-plaintext">
                                    <strong>{{ $finishedGoodTransfer->item->name }}</strong><br>
                                    <small class="text-muted">{{ $finishedGoodTransfer->item->item_code }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Craftsman</label>
                                <p class="form-control-plaintext">
                                    {{ $finishedGoodTransfer->craftsman ? $finishedGoodTransfer->craftsman->full_name : 'Not assigned' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">From Workshop</label>
                                <p class="form-control-plaintext">{{ $finishedGoodTransfer->from_workshop }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">To Location</label>
                                <p class="form-control-plaintext">{{ $finishedGoodTransfer->to_location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info fs-6">{{ $finishedGoodTransfer->quantity }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quality Check</label>
                                <p class="form-control-plaintext">
                                    @if($finishedGoodTransfer->quality_check_passed !== null)
                                        @if($finishedGoodTransfer->quality_check_passed)
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
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Transferred By</label>
                                <p class="form-control-plaintext">
                                    {{ $finishedGoodTransfer->transferredBy ? $finishedGoodTransfer->transferredBy->name : 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Received By</label>
                                <p class="form-control-plaintext">
                                    {{ $finishedGoodTransfer->receivedBy ? $finishedGoodTransfer->receivedBy->name : 'Not received yet' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($finishedGoodTransfer->qualityCheckBy)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Quality Check By</label>
                                <p class="form-control-plaintext">{{ $finishedGoodTransfer->qualityCheckBy->name }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($finishedGoodTransfer->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $finishedGoodTransfer->notes }}
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
                        <a href="{{ route('finished-good-transfers.edit', $finishedGoodTransfer) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Transfer
                        </a>

                        @if($finishedGoodTransfer->status === 'pending')
                        <button class="btn btn-info" onclick="qualityCheck()">
                            <i class="fas fa-search me-1"></i>Quality Check
                        </button>
                        @endif

                        @if($finishedGoodTransfer->status === 'quality_check' && $finishedGoodTransfer->quality_check_passed)
                        <button class="btn btn-success" onclick="completeTransfer()">
                            <i class="fas fa-check me-1"></i>Complete Transfer
                        </button>
                        @endif

                        <a href="{{ route('finished-good-transfers.export-pdf', $finishedGoodTransfer) }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>

                        <a href="{{ route('finished-good-transfers.index') }}" class="btn btn-outline-secondary">
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
                    <form action="{{ route('finished-good-transfers.update-status', $finishedGoodTransfer) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $finishedGoodTransfer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="quality_check" {{ $finishedGoodTransfer->status == 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                                <option value="completed" {{ $finishedGoodTransfer->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $finishedGoodTransfer->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Add status update notes...">{{ $finishedGoodTransfer->notes }}</textarea>
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
                        <strong>Name:</strong> {{ $finishedGoodTransfer->item->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Code:</strong> {{ $finishedGoodTransfer->item->item_code }}
                    </div>
                    <div class="mb-2">
                        <strong>Category:</strong> {{ $finishedGoodTransfer->item->category }}
                    </div>
                    <div class="mb-2">
                        <strong>Material:</strong> {{ $finishedGoodTransfer->item->material }}
                    </div>
                    @if($finishedGoodTransfer->item->gemstone)
                    <div class="mb-2">
                        <strong>Gemstone:</strong> {{ $finishedGoodTransfer->item->gemstone }}
                    </div>
                    @endif
                    <div class="mb-2">
                        <strong>Current Stock:</strong> 
                        <span class="badge bg-{{ $finishedGoodTransfer->item->stock_status_color }}">
                            {{ $finishedGoodTransfer->item->current_stock }}
                        </span>
                    </div>
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
            <form action="{{ route('finished-good-transfers.quality-check', $finishedGoodTransfer) }}" method="POST">
                @csrf
                <div class="modal-body">
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
                                  placeholder="Add quality check notes...">{{ $finishedGoodTransfer->notes }}</textarea>
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
            <form action="{{ route('finished-good-transfers.complete-transfer', $finishedGoodTransfer) }}" method="POST">
                @csrf
                <div class="modal-body">
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
                                  placeholder="Add completion notes...">{{ $finishedGoodTransfer->notes }}</textarea>
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
function qualityCheck() {
    const modal = new bootstrap.Modal(document.getElementById('qualityCheckModal'));
    modal.show();
}

function completeTransfer() {
    const modal = new bootstrap.Modal(document.getElementById('completeTransferModal'));
    modal.show();
}
</script>
@endpush
