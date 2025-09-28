@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Invoice Details</h1>
                    <p class="text-muted">Invoice #{{ $invoice->invoice_number }}</p>
                </div>
                <div>
                    <div class="btn-group" role="group">
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Invoices
                        </a>
                        
                        @if($invoice->status === 'draft')
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        @endif
                        
                        <a href="{{ route('invoices.export-pdf', $invoice) }}" class="btn btn-outline-info">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                        
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @if($invoice->status === 'draft')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('invoices.duplicate', $invoice) }}"
                                           onclick="return confirm('Are you sure you want to duplicate this invoice?')">
                                            <i class="fas fa-copy me-2"></i> Duplicate
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Invoice Information -->
        <div class="col-lg-8">
            <!-- Invoice Header -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice Information</h6>
                        <div>
                            <span class="badge bg-{{ $invoice->status_color }} fs-6">
                                {{ $invoice->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Bill To:</h6>
                            <p class="mb-1"><strong>{{ $invoice->customer->full_name }}</strong></p>
                            <p class="mb-1">{{ $invoice->customer->customer_code }}</p>
                            @if($invoice->customer->email)
                                <p class="mb-1">{{ $invoice->customer->email }}</p>
                            @endif
                            @if($invoice->customer->phone)
                                <p class="mb-1">{{ $invoice->customer->phone }}</p>
                            @endif
                            @if($invoice->customer->address)
                                <p class="mb-1">{{ $invoice->customer->address }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Invoice Details:</h6>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                            @if($invoice->is_overdue)
                                <p class="mb-1 text-danger">
                                    <strong>Overdue:</strong> {{ $invoice->days_overdue }} days
                                </p>
                            @endif
                            <p class="mb-1"><strong>Sales Assistant:</strong> {{ $invoice->salesAssistant->full_name }}</p>
                            @if($invoice->salesOrder)
                                <p class="mb-1"><strong>Sales Order:</strong> {{ $invoice->salesOrder->order_number }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Tax</th>
                                    <th width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->transactionItems as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->item->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->item->item_code }}</small>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->quantity, 3) }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            @if($item->discount_percentage > 0)
                                                {{ number_format($item->discount_percentage, 1) }}%
                                                <br>
                                                <small class="text-muted">({{ number_format($item->discount_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tax_percentage > 0)
                                                {{ number_format($item->tax_percentage, 1) }}%
                                                <br>
                                                <small class="text-muted">({{ number_format($item->tax_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><strong>{{ number_format($item->total_price, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($invoice->notes || $invoice->terms_conditions)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                    </div>
                    <div class="card-body">
                        @if($invoice->notes)
                            <div class="mb-3">
                                <h6 class="text-primary">Notes:</h6>
                                <p class="text-muted">{{ $invoice->notes }}</p>
                            </div>
                        @endif
                        
                        @if($invoice->terms_conditions)
                            <div>
                                <h6 class="text-primary">Terms & Conditions:</h6>
                                <p class="text-muted">{{ $invoice->terms_conditions }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Invoice Summary -->
        <div class="col-lg-4">
            <!-- Status Management -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Management</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.update-status', $invoice) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Current Status</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="draft" {{ $invoice->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ $invoice->status === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ $invoice->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency->code }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span>{{ number_format($invoice->discount_amount, 2) }} {{ $invoice->currency->code }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency->code }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency->code }}</strong>
                    </div>
                    
                    @if($invoice->payment_method)
                        <div class="mt-3">
                            <strong>Payment Method:</strong>
                            <span class="badge bg-{{ $invoice->payment_method_color }} ms-2">
                                {{ $invoice->payment_method_label }}
                            </span>
                        </div>
                    @endif
                    
                    @if($invoice->payment_terms)
                        <div class="mt-2">
                            <strong>Payment Terms:</strong>
                            <p class="text-muted mb-0">{{ $invoice->payment_terms }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Invoice Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Invoice Created</h6>
                                <p class="timeline-text">{{ $invoice->created_at->format('M d, Y H:i') }}</p>
                                @if($invoice->createdBy)
                                    <small class="text-muted">by {{ $invoice->createdBy->name }}</small>
                                @endif
                            </div>
                        </div>
                        
                        @if($invoice->sent_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Invoice Sent</h6>
                                    <p class="timeline-text">{{ $invoice->sent_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($invoice->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Invoice Paid</h6>
                                    <p class="timeline-text">{{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
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
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0 0 5px 0;
    font-size: 13px;
    color: #6c757d;
}
</style>
@endsection