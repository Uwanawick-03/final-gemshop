@extends('layouts.app')

@section('title', 'Alteration Commission Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Alteration Commission Details</h1>
        <div>
            <a href="{{ route('alteration-commissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Commissions
            </a>
            @if($alterationCommission->status !== 'completed')
                <a href="{{ route('alteration-commissions.edit', $alterationCommission) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
            <a href="{{ route('alteration-commissions.export-pdf', $alterationCommission) }}" class="btn btn-info">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Commission Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Information</h6>
                    <div>
                        <span class="badge badge-{{ $alterationCommission->status_color }} badge-lg">
                            {{ $alterationCommission->status_label }}
                        </span>
                        <span class="badge badge-{{ $alterationCommission->payment_status_color }} badge-lg ml-2">
                            {{ $alterationCommission->payment_status_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Commission Number:</td>
                                    <td>{{ $alterationCommission->commission_number }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Customer:</td>
                                    <td>
                                        <div>
                                            <strong>{{ $alterationCommission->customer->first_name }} {{ $alterationCommission->customer->last_name }}</strong><br>
                                            <small class="text-muted">{{ $alterationCommission->customer->email }}</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Item:</td>
                                    <td>
                                        @if($alterationCommission->item)
                                            <div>
                                                <strong>{{ $alterationCommission->item->name }}</strong><br>
                                                <small class="text-muted">{{ $alterationCommission->item->item_code }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">No item specified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Alteration Type:</td>
                                    <td>
                                        <span class="badge badge-{{ $alterationCommission->alteration_type_color }}">
                                            {{ $alterationCommission->alteration_type_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Commission Date:</td>
                                    <td>{{ $alterationCommission->commission_date->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Commission Amount:</td>
                                    <td>
                                        <div>
                                            <strong class="h5 text-primary">{{ number_format($alterationCommission->commission_amount, 2) }}</strong><br>
                                            <small class="text-muted">{{ $alterationCommission->currency->code }}</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Sales Assistant:</td>
                                    <td>
                                        @if($alterationCommission->salesAssistant)
                                            {{ $alterationCommission->salesAssistant->first_name }} {{ $alterationCommission->salesAssistant->last_name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Craftsman:</td>
                                    <td>
                                        @if($alterationCommission->craftsman)
                                            {{ $alterationCommission->craftsman->first_name }} {{ $alterationCommission->craftsman->last_name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Start Date:</td>
                                    <td>
                                        @if($alterationCommission->start_date)
                                            {{ $alterationCommission->start_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not started</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-gray-600">Completion Date:</td>
                                    <td>
                                        @if($alterationCommission->completion_date)
                                            {{ $alterationCommission->completion_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not completed</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($alterationCommission->description)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-gray-600">Description:</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $alterationCommission->description }}
                            </div>
                        </div>
                    @endif

                    @if($alterationCommission->notes)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-gray-600">Notes:</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $alterationCommission->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Amount:</span>
                                <strong>{{ number_format($alterationCommission->commission_amount, 2) }} {{ $alterationCommission->currency->code }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Paid Amount:</span>
                                <strong class="text-success">{{ number_format($alterationCommission->paid_amount ?? 0, 2) }} {{ $alterationCommission->currency->code }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Remaining Amount:</span>
                                <strong class="text-danger">{{ number_format($alterationCommission->remaining_amount, 2) }} {{ $alterationCommission->currency->code }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-{{ $alterationCommission->payment_status_color }}" 
                                     role="progressbar" 
                                     style="width: {{ $alterationCommission->progress_percentage }}%"
                                     aria-valuenow="{{ $alterationCommission->progress_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $alterationCommission->progress_percentage }}% Paid
                                </div>
                            </div>
                            @if($alterationCommission->payment_date)
                                <small class="text-muted">Last Payment: {{ $alterationCommission->payment_date->format('M d, Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $alterationCommission->status === 'pending' || $alterationCommission->status === 'in_progress' || $alterationCommission->status === 'completed' ? 'active' : '' }}">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pending</h6>
                                <p class="timeline-text">Commission created and waiting to be started</p>
                                <small class="text-muted">{{ $alterationCommission->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>

                        @if($alterationCommission->status === 'in_progress' || $alterationCommission->status === 'completed')
                            <div class="timeline-item {{ $alterationCommission->status === 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">In Progress</h6>
                                    <p class="timeline-text">Alteration work is being performed</p>
                                    @if($alterationCommission->start_date)
                                        <small class="text-muted">Started: {{ $alterationCommission->start_date->format('M d, Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($alterationCommission->status === 'completed')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Completed</h6>
                                    <p class="timeline-text">Alteration work is completed</p>
                                    @if($alterationCommission->completion_date)
                                        <small class="text-muted">Completed: {{ $alterationCommission->completion_date->format('M d, Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($alterationCommission->status === 'cancelled')
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Cancelled</h6>
                                    <p class="timeline-text">Commission has been cancelled</p>
                                    <small class="text-muted">{{ $alterationCommission->updated_at->format('M d, Y H:i') }}</small>
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
                    @if($alterationCommission->status === 'pending')
                        <form method="POST" action="{{ route('alteration-commissions.update-status', $alterationCommission) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-play"></i> Start Work
                            </button>
                        </form>
                    @endif

                    @if($alterationCommission->status === 'in_progress')
                        <form method="POST" action="{{ route('alteration-commissions.update-status', $alterationCommission) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <div class="form-group">
                                <label for="completion_date">Completion Date</label>
                                <input type="date" class="form-control" id="completion_date" name="completion_date" 
                                       value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        </form>
                    @endif

                    @if($alterationCommission->status !== 'completed' && $alterationCommission->status !== 'cancelled')
                        <form method="POST" action="{{ route('alteration-commissions.update-status', $alterationCommission) }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger btn-block" 
                                    onclick="return confirm('Are you sure you want to cancel this commission?')">
                                <i class="fas fa-times"></i> Cancel Commission
                            </button>
                        </form>
                    @endif

                    @if($alterationCommission->status !== 'cancelled')
                        <button type="button" class="btn btn-warning btn-block mb-3" data-toggle="modal" data-target="#paymentModal">
                            <i class="fas fa-credit-card"></i> Record Payment
                        </button>
                    @endif

                    <a href="{{ route('alteration-commissions.export-pdf', $alterationCommission) }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Commission Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5 class="text-primary">{{ $alterationCommission->alteration_type_label }}</h5>
                        <p class="text-muted mb-2">{{ $alterationCommission->commission_number }}</p>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h6 class="text-primary">{{ number_format($alterationCommission->commission_amount, 2) }}</h6>
                                    <small class="text-muted">Total Amount</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">{{ number_format($alterationCommission->paid_amount ?? 0, 2) }}</h6>
                                <small class="text-muted">Paid Amount</small>
                            </div>
                        </div>
                        @if($alterationCommission->duration)
                            <div class="mt-3">
                                <h6 class="text-info">{{ $alterationCommission->duration }} days</h6>
                                <small class="text-muted">Duration</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Commission Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Created:</span>
                        <strong>{{ $alterationCommission->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Days Since Created:</span>
                        <strong>{{ $alterationCommission->created_at->diffInDays(now()) }} days</strong>
                    </div>
                    @if($alterationCommission->is_overdue)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Days Overdue:</span>
                            <strong class="text-danger">{{ $alterationCommission->days_overdue }} days</strong>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Created By:</span>
                        <strong>{{ $alterationCommission->createdBy->name ?? 'System' }}</strong>
                    </div>
                    @if($alterationCommission->updatedBy)
                        <div class="d-flex justify-content-between">
                            <span>Last Updated By:</span>
                            <strong>{{ $alterationCommission->updatedBy->name }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('alteration-commissions.update-payment', $alterationCommission) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paid_amount">Payment Amount</label>
                        <input type="number" step="0.01" min="0.01" max="{{ $alterationCommission->remaining_amount }}" 
                               class="form-control" id="paid_amount" name="paid_amount" required>
                        <small class="form-text text-muted">
                            Maximum: {{ number_format($alterationCommission->remaining_amount, 2) }} {{ $alterationCommission->currency->code }}
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" 
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
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
