@extends('layouts.app')

@section('title', 'Stock Adjustment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-adjust me-2"></i>Stock Adjustment Details</h4>
        <div class="small text-muted">Adjustment #{{ $stockAdjustment->adjustment_number }}</div>
    </div>
    <div>
        <a href="{{ route('stock-adjustments.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        @if($stockAdjustment->status === 'pending')
            <a href="{{ route('stock-adjustments.edit', $stockAdjustment) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        @endif
    </div>
</div>

<div class="row">
    <!-- Adjustment Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Adjustment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Adjustment Number:</strong> {{ $stockAdjustment->adjustment_number }}</p>
                        <p><strong>Adjustment Date:</strong> {{ $stockAdjustment->adjustment_date->format('M d, Y') }}</p>
                        <p><strong>Type:</strong> 
                            <span class="badge bg-{{ $stockAdjustment->type_color }}">
                                {{ $stockAdjustment->type_label }}
                            </span>
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $stockAdjustment->status_color }}">
                                {{ $stockAdjustment->status_label }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Reason:</strong> {{ ucfirst(str_replace('_', ' ', $stockAdjustment->reason)) }}</p>
                        <p><strong>Total Items:</strong> {{ $stockAdjustment->total_items }}</p>
                        <p><strong>Created By:</strong> {{ $stockAdjustment->createdBy?->name ?? 'Unknown' }}</p>
                        <p><strong>Created:</strong> {{ $stockAdjustment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @if($stockAdjustment->notes)
                    <div class="mt-3">
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $stockAdjustment->notes }}</p>
                    </div>
                @endif
                @if($stockAdjustment->approvedBy)
                    <div class="mt-3">
                        <strong>Approved By:</strong> {{ $stockAdjustment->approvedBy->name }}<br>
                        <strong>Approved At:</strong> {{ $stockAdjustment->approved_at->format('M d, Y H:i') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Adjustment Items</h5>
            </div>
            <div class="card-body">
                @if($stockAdjustment->adjustmentItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Current Qty</th>
                                    <th>Adjusted Qty</th>
                                    <th>Difference</th>
                                    <th>Unit Cost</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockAdjustment->adjustmentItems as $item)
                                    <tr>
                                        <td>{{ $item->item_code }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ number_format($item->current_quantity, 2) }}</td>
                                        <td>{{ number_format($item->adjusted_quantity, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->difference_quantity > 0 ? 'success' : ($item->difference_quantity < 0 ? 'danger' : 'secondary') }}">
                                                {{ $item->difference_quantity > 0 ? '+' : '' }}{{ number_format($item->difference_quantity, 2) }}
                                            </span>
                                        </td>
                                        <td>{{ displayAmount($item->unit_cost) }}</td>
                                        <td><strong>{{ displayAmount($item->total_value) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No items found for this adjustment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <!-- Status Actions -->
        @if($stockAdjustment->status === 'pending')
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock-adjustments.approve', $stockAdjustment) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" 
                                onclick="return confirm('Are you sure you want to approve this adjustment? This will update the inventory quantities.')">
                            <i class="fas fa-check me-1"></i> Approve & Apply
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-1"></i> Reject
                    </button>
                </div>
            </div>
        @endif

        <!-- Adjustment Summary -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Adjustment Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Items:</span>
                    <span>{{ $stockAdjustment->adjustmentItems->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Increases:</span>
                    <span class="text-success">{{ $stockAdjustment->adjustmentItems->where('difference_quantity', '>', 0)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Decreases:</span>
                    <span class="text-danger">{{ $stockAdjustment->adjustmentItems->where('difference_quantity', '<', 0)->count() }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Value:</strong>
                    <strong>{{ displayAmount($stockAdjustment->adjustmentItems->sum('total_value')) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($stockAdjustment->status === 'pending')
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('stock-adjustments.reject', $stockAdjustment) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" 
                                      placeholder="Please provide a reason for rejecting this adjustment..." required></textarea>
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
@endif
@endsection
