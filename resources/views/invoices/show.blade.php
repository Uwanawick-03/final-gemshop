@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>Invoice #{{ $invoice->invoice_number }}</h2>
    <div class="btn-group">
        @if($invoice->status === 'draft')
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Invoice
            </a>
        @endif
        
        <a href="{{ route('invoices.export-pdf', $invoice) }}" class="btn btn-primary">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
        
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-cog me-2"></i>More Actions
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="duplicateInvoice()">
                    <i class="fas fa-copy me-2"></i>Duplicate Invoice
                </a></li>
                @if($invoice->customer && $invoice->customer->email)
                <li><a class="dropdown-item" href="#" onclick="sendInvoiceEmail()">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('invoices.index') }}">
                    <i class="fas fa-arrow-left me-2"></i>Back to Invoices
                </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Invoice Header -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice Details</h5>
                <span class="badge bg-{{ $invoice->status_badge }} fs-6">{{ ucfirst($invoice->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Invoice Information</h6>
                        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                        <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date?->format('M d, Y') }}</p>
                        <p><strong>Due Date:</strong> 
                            <span class="{{ $invoice->is_overdue ? 'text-danger fw-bold' : '' }}">
                                {{ $invoice->due_date?->format('M d, Y') }}
                            </span>
                            @if($invoice->is_overdue)
                                <span class="badge bg-danger ms-2">{{ $invoice->days_overdue }} days overdue</span>
                            @endif
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $invoice->status_badge }}">{{ ucfirst($invoice->status) }}</span>
                        </p>
                        @if($invoice->sales_order_id)
                            <p><strong>Sales Order:</strong> 
                                <a href="{{ route('sales-orders.show', $invoice->sales_order_id) }}">
                                    {{ $invoice->salesOrder?->so_number }}
                                </a>
                            </p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Customer Information</h6>
                        <p><strong>Customer:</strong> {{ $invoice->customer?->full_name }}</p>
                        <p><strong>Customer Code:</strong> {{ $invoice->customer?->customer_code }}</p>
                        <p><strong>Sales Assistant:</strong> {{ $invoice->salesAssistant?->full_name }}</p>
                        <p><strong>Currency:</strong> {{ $invoice->currency?->code }} ({{ $invoice->currency?->name }})</p>
                        @if($invoice->currency_id && !$invoice->currency?->is_base_currency)
                            <p><strong>Exchange Rate:</strong> {{ $invoice->exchange_rate }}</p>
                        @endif
                    </div>
                </div>
                
                @if($invoice->payment_terms || $invoice->payment_method)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Payment Information</h6>
                        @if($invoice->payment_terms)
                            <p><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</p>
                        @endif
                        @if($invoice->payment_method)
                            <p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($invoice->notes)
                    <div class="mt-3">
                        <h6 class="text-muted">Notes</h6>
                        <p>{{ $invoice->notes }}</p>
                    </div>
                @endif
                
                @if($invoice->terms_conditions)
                    <div class="mt-3">
                        <h6 class="text-muted">Terms & Conditions</h6>
                        <p>{{ $invoice->terms_conditions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Invoice Items</h5>
            </div>
            <div class="card-body">
                @if($invoice->transactionItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->transactionItems as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->item->name }}</strong>
                                                @if($item->item->item_code)
                                                    <br><small class="text-muted">{{ $item->item->item_code }}</small>
                                                @endif
                                                @if($item->item->description)
                                                    <br><small class="text-muted">{{ $item->item->description }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }} {{ $item->item->unit }}</td>
                                        <td>{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            @if($item->discount_amount > 0)
                                                {{ $item->discount_percentage }}% 
                                                <br><small class="text-muted">({{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($item->discount_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tax_amount > 0)
                                                {{ $item->tax_percentage }}% 
                                                <br><small class="text-muted">({{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($item->tax_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><strong>{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($item->total_price, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-muted">No items found for this invoice.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Invoice Summary -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Invoice Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                @if($invoice->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Discount:</span>
                    <span class="text-success">-{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
                @endif
                @if($invoice->tax_amount > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Tax:</span>
                    <span class="text-info">+{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total Amount:</span>
                    <span>{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Status Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($invoice->status === 'draft')
                        <form action="{{ route('invoices.update-status', $invoice) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="status" value="sent">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this invoice as sent?')">
                                <i class="fas fa-paper-plane me-2"></i>Mark as Sent
                            </button>
                        </form>
                    @endif
                    
                    @if($invoice->status === 'sent')
                        <form action="{{ route('invoices.update-status', $invoice) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this invoice as paid?')">
                                <i class="fas fa-check me-2"></i>Mark as Paid
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($invoice->status, ['draft', 'sent']))
                        <form action="{{ route('invoices.update-status', $invoice) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Cancel this invoice?')">
                                <i class="fas fa-times me-2"></i>Cancel Invoice
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($invoice->status, ['draft', 'cancelled']))
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                <i class="fas fa-trash me-2"></i>Delete Invoice
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        @if($invoice->customer)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $invoice->customer->full_name }}</p>
                    <p><strong>Code:</strong> {{ $invoice->customer->customer_code }}</p>
                    <p><strong>Email:</strong> {{ $invoice->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>
                    @if($invoice->customer->full_address)
                        <p><strong>Address:</strong> {{ $invoice->customer->full_address }}</p>
                    @endif
                    <p><strong>Type:</strong> {{ ucfirst($invoice->customer->customer_type) }}</p>
                </div>
            </div>
        @endif

        <!-- Invoice Timeline -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Invoice Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Invoice Created</h6>
                            <small class="text-muted">{{ $invoice->created_at->format('M d, Y H:i A') }}</small>
                            @if($invoice->createdBy)
                                <br><small class="text-muted">by {{ $invoice->createdBy->name }}</small>
                            @endif
                        </div>
                    </div>
                    
                    @if($invoice->sent_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6>Invoice Sent</h6>
                            <small class="text-muted">{{ $invoice->sent_at->format('M d, Y H:i A') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($invoice->paid_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>Invoice Paid</h6>
                            <small class="text-muted">{{ $invoice->paid_at->format('M d, Y H:i A') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for actions -->
<form id="duplicateForm" action="{{ route('invoices.duplicate', $invoice) }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="sendEmailForm" action="{{ route('invoices.send-email', $invoice) }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
</style>

<script>
function duplicateInvoice() {
    if (confirm('Are you sure you want to duplicate this invoice?')) {
        document.getElementById('duplicateForm').submit();
    }
}

function sendInvoiceEmail() {
    if (confirm('Are you sure you want to send this invoice via email?')) {
        document.getElementById('sendEmailForm').submit();
    }
}
</script>
@endsection