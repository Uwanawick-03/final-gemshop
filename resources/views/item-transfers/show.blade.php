@extends('layouts.app')

@section('title', 'Item Transfer Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Item Transfer Details</h1>
        <div>
            <a href="{{ route('item-transfers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transfers
            </a>
            @if($itemTransfer->status !== 'completed')
                <a href="{{ route('item-transfers.edit', $itemTransfer) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
            <a href="{{ route('item-transfers.export-pdf', $itemTransfer) }}" class="btn btn-info">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Transfer Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Transfer Information</h6>
                    <span class="badge badge-{{ $itemTransfer->status_color }} badge-lg">
                        {{ $itemTransfer->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Reference Number:</td>
                                    <td>{{ $itemTransfer->reference_number }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Item:</td>
                                    <td>
                                        <div>
                                            <strong>{{ $itemTransfer->item->name }}</strong><br>
                                            <small class="text-muted">{{ $itemTransfer->item->item_code }}</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">From Location:</td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="fas fa-map-marker-alt"></i> {{ $itemTransfer->from_location }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">To Location:</td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-map-marker-alt"></i> {{ $itemTransfer->to_location }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Quantity:</td>
                                    <td>
                                        <span class="h5 text-primary">{{ number_format($itemTransfer->quantity, 3) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Transfer Date:</td>
                                    <td>
                                        {{ $itemTransfer->transfer_date->format('M d, Y') }}
                                        @if($itemTransfer->is_overdue)
                                            <br><small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                {{ $itemTransfer->days_overdue }} days overdue
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Reason:</td>
                                    <td>
                                        <span class="badge badge-{{ $itemTransfer->reason_color }}">
                                            {{ $itemTransfer->reason_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Transferred By:</td>
                                    <td>
                                        @if($itemTransfer->transferredBy)
                                            {{ $itemTransfer->transferredBy->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Received By:</td>
                                    <td>
                                        @if($itemTransfer->receivedBy)
                                            {{ $itemTransfer->receivedBy->name }}
                                        @else
                                            <span class="text-muted">Not received yet</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Received At:</td>
                                    <td>
                                        @if($itemTransfer->received_at)
                                            {{ $itemTransfer->received_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Not received yet</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($itemTransfer->notes)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-gray-600">Notes:</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $itemTransfer->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $itemTransfer->status === 'pending' || $itemTransfer->status === 'in_transit' || $itemTransfer->status === 'completed' ? 'active' : '' }}">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pending</h6>
                                <p class="timeline-text">Transfer created and waiting to be processed</p>
                                <small class="text-muted">{{ $itemTransfer->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>

                        @if($itemTransfer->status === 'in_transit' || $itemTransfer->status === 'completed')
                            <div class="timeline-item {{ $itemTransfer->status === 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">In Transit</h6>
                                    <p class="timeline-text">Items are being moved between locations</p>
                                    @if($itemTransfer->updated_at > $itemTransfer->created_at)
                                        <small class="text-muted">{{ $itemTransfer->updated_at->format('M d, Y H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($itemTransfer->status === 'completed')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Completed</h6>
                                    <p class="timeline-text">Transfer completed and stock updated</p>
                                    @if($itemTransfer->received_at)
                                        <small class="text-muted">{{ $itemTransfer->received_at->format('M d, Y H:i') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($itemTransfer->status === 'cancelled')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Cancelled</h6>
                                    <p class="timeline-text">Transfer has been cancelled</p>
                                    <small class="text-muted">{{ $itemTransfer->updated_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($itemTransfer->status === 'pending')
                        <form method="POST" action="{{ route('item-transfers.update-status', $itemTransfer) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="in_transit">
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-truck"></i> Mark as In Transit
                            </button>
                        </form>
                    @endif

                    @if($itemTransfer->status === 'in_transit')
                        <form method="POST" action="{{ route('item-transfers.update-status', $itemTransfer) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <div class="form-group">
                                <label for="received_by">Received By</label>
                                <select class="form-control" id="received_by" name="received_by">
                                    <option value="">Select User</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if($itemTransfer->status !== 'completed' && $itemTransfer->status !== 'cancelled')
                        <form method="POST" action="{{ route('item-transfers.update-status', $itemTransfer) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger btn-block" 
                                    onclick="return confirm('Are you sure you want to cancel this transfer?')">
                                <i class="fas fa-times"></i> Cancel Transfer
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('item-transfers.export-pdf', $itemTransfer) }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Item Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Item Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-gem fa-3x text-primary mb-3"></i>
                        <h5 class="text-primary">{{ $itemTransfer->item->name }}</h5>
                        <p class="text-muted mb-2">{{ $itemTransfer->item->item_code }}</p>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h6 class="text-success">{{ number_format($itemTransfer->item->current_stock, 2) }}</h6>
                                    <small class="text-muted">Current Stock</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-primary">{{ number_format($itemTransfer->quantity, 2) }}</h6>
                                <small class="text-muted">Transfer Qty</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transfer Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Transfer Date:</span>
                        <strong>{{ $itemTransfer->transfer_date->format('M d, Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Days Since Created:</span>
                        <strong>{{ $itemTransfer->created_at->diffInDays(now()) }} days</strong>
                    </div>
                    @if($itemTransfer->is_overdue)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Days Overdue:</span>
                            <strong class="text-danger">{{ $itemTransfer->days_overdue }} days</strong>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Created By:</span>
                        <strong>{{ $itemTransfer->createdBy->name ?? 'System' }}</strong>
                    </div>
                    @if($itemTransfer->updatedBy)
                        <div class="d-flex justify-content-between">
                            <span>Last Updated By:</span>
                            <strong>{{ $itemTransfer->updatedBy->name }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 14px;
    color: #6c757d;
    margin: 0 0 5px 0;
}
</style>
@endsection
