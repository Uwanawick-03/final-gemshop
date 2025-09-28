@extends('layouts.app')

@section('title', 'GRN Details - ' . $grn->grn_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-receipt me-2"></i>GRN Details - {{ $grn->grn_number }}</h2>
    <div>
        <a href="{{ route('grns.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back to GRNs
        </a>
        @if($grn->status === 'draft')
            <a href="{{ route('grns.edit', $grn) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
        @endif
        <a href="{{ route('grns.export-pdf', $grn) }}" class="btn btn-primary me-2">
            <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
        <button class="btn btn-secondary" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Print
        </button>
    </div>
</div>

<div class="row">
    <!-- GRN Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">GRN Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>GRN Number:</strong></td>
                                <td>{{ $grn->grn_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Supplier:</strong></td>
                                <td>
                                    {{ $grn->supplier->company_name }}<br>
                                    <small class="text-muted">{{ $grn->supplier->contact_person }}</small>
                                </td>
                            </tr>
                            @if($grn->purchase_order)
                            <tr>
                                <td><strong>Purchase Order:</strong></td>
                                <td>{{ $grn->purchase_order->po_number }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>GRN Date:</strong></td>
                                <td>{{ $grn->grn_date->format('M j, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Received Date:</strong></td>
                                <td>{{ $grn->received_date->format('M j, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $grn->status_color }}">
                                        {{ $grn->status_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Currency:</strong></td>
                                <td>{{ $grn->currency->code }} ({{ $grn->currency->name }})</td>
                            </tr>
                            @if($grn->exchange_rate != 1)
                            <tr>
                                <td><strong>Exchange Rate:</strong></td>
                                <td>{{ $grn->exchange_rate }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Received By:</strong></td>
                                <td>{{ $grn->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $grn->created_at->format('M j, Y g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($grn->delivery_person || $grn->vehicle_number)
                <hr>
                <h6>Delivery Information</h6>
                <div class="row">
                    @if($grn->delivery_person)
                    <div class="col-md-6">
                        <strong>Delivery Person:</strong> {{ $grn->delivery_person }}
                    </div>
                    @endif
                    @if($grn->vehicle_number)
                    <div class="col-md-6">
                        <strong>Vehicle Number:</strong> {{ $grn->vehicle_number }}
                    </div>
                    @endif
                </div>
                @endif
                
                @if($grn->delivery_notes)
                <hr>
                <h6>Delivery Notes</h6>
                <p>{{ $grn->delivery_notes }}</p>
                @endif
                
                @if($grn->notes)
                <hr>
                <h6>Notes</h6>
                <p>{{ $grn->notes }}</p>
                @endif
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Received Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grn->transactionItems as $item)
                            <tr>
                                <td>{{ $item->item->item_code }}</td>
                                <td>{{ $item->item->name }}</td>
                                <td>{{ $item->quantity }} {{ $item->item->unit }}</td>
                                <td>{{ $grn->currency->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                                <td>
                                    @if($item->discount_amount > 0)
                                        {{ $grn->currency->symbol }}{{ number_format($item->discount_amount, 2) }}
                                        @if($item->discount_percentage > 0)
                                            <br><small class="text-muted">({{ $item->discount_percentage }}%)</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->tax_amount > 0)
                                        {{ $grn->currency->symbol }}{{ number_format($item->tax_amount, 2) }}
                                        @if($item->tax_percentage > 0)
                                            <br><small class="text-muted">({{ $item->tax_percentage }}%)</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><strong>{{ $grn->currency->symbol }}{{ number_format($item->total_price, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                <td><strong>{{ $grn->currency->symbol }}{{ number_format($grn->subtotal, 2) }}</strong></td>
                            </tr>
                            @if($grn->discount_amount > 0)
                            <tr>
                                <td colspan="6" class="text-end"><strong>Discount:</strong></td>
                                <td><strong>{{ $grn->currency->symbol }}{{ number_format($grn->discount_amount, 2) }}</strong></td>
                            </tr>
                            @endif
                            @if($grn->tax_amount > 0)
                            <tr>
                                <td colspan="6" class="text-end"><strong>Tax:</strong></td>
                                <td><strong>{{ $grn->currency->symbol }}{{ number_format($grn->tax_amount, 2) }}</strong></td>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <td colspan="6" class="text-end"><strong>Total Amount:</strong></td>
                                <td><strong>{{ $grn->currency->symbol }}{{ number_format($grn->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status and Actions -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Status Management</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('grns.update-status', $grn) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Current Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" {{ $grn->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="received" {{ $grn->status === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="verified" {{ $grn->status === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="completed" {{ $grn->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $grn->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('grns.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>View All GRNs
                    </a>
                    @if($grn->status === 'draft')
                        <a href="{{ route('grns.edit', $grn) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Edit GRN
                        </a>
                    @endif
                    <a href="{{ route('grns.export-pdf', $grn) }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-1"></i>Export PDF
                    </a>
                    <button class="btn btn-outline-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Print GRN
                    </button>
                    @if($grn->purchase_order)
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-1"></i>View Purchase Order
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, .col-lg-4 {
        display: none !important;
    }
    .col-lg-8 {
        width: 100% !important;
    }
}
</style>
@endsection












