@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-file-invoice me-2"></i>PO {{ $purchaseOrder->po_number }}</h4>
        <div class="small text-muted">{{ $purchaseOrder->supplier?->company_name ?? '—' }} • {{ $purchaseOrder->order_date?->format('Y-m-d') }}</div>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($purchaseOrder->status, ['draft', 'pending']))
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        @endif
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Order Information</h5></div><div class="card-body"><div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-bold">Status</label><p class="mb-0"><span class="badge bg-secondary">{{ ucwords(str_replace('_',' ', $purchaseOrder->status)) }}</span></p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Expected Delivery</label><p class="mb-0">{{ $purchaseOrder->expected_delivery_date?->format('Y-m-d') ?? '—' }}</p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Currency</label><p class="mb-0">{{ $purchaseOrder->currency?->code ?? '—' }}</p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Exchange Rate</label><p class="mb-0">{{ number_format($purchaseOrder->exchange_rate, 4) }}</p></div>
        </div></div></div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4"><div class="card-header"><h5 class="mb-0">Amounts</h5></div><div class="card-body"><div class="row g-3">
            <div class="col-md-6"><label class="form-label fw-bold">Subtotal</label><p class="mb-0">{{ number_format($purchaseOrder->subtotal, 2) }}</p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Tax</label><p class="mb-0">{{ number_format($purchaseOrder->tax_amount, 2) }}</p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Discount</label><p class="mb-0">{{ number_format($purchaseOrder->discount_amount, 2) }}</p></div>
            <div class="col-md-6"><label class="form-label fw-bold">Total</label><p class="mb-0"><strong class="text-success">{{ number_format($purchaseOrder->total_amount, 2) }}</strong></p></div>
        </div></div></div>
    </div>
</div>

@if($purchaseOrder->transactionItems->count() > 0)
<div class="row"><div class="col-12"><div class="card mb-4"><div class="card-header"><h5 class="mb-0">Items Ordered</h5></div><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th>Discount</th><th>Tax</th><th>Total</th></tr></thead><tbody>@foreach($purchaseOrder->transactionItems as $item)<tr><td>{{ $item->item->item_code }} - {{ $item->item->name }}</td><td>{{ $item->quantity }}</td><td>{{ number_format($item->unit_price, 2) }}</td><td>{{ number_format($item->discount_amount, 2) }}</td><td>{{ number_format($item->tax_amount, 2) }}</td><td><strong>{{ number_format($item->total_price, 2) }}</strong></td></tr>@endforeach</tbody></table></div></div></div></div></div>
@endif

@if($purchaseOrder->notes)
<div class="row"><div class="col-12"><div class="card mb-4"><div class="card-header"><h5 class="mb-0">Notes</h5></div><div class="card-body"><p class="mb-0">{{ $purchaseOrder->notes }}</p></div></div></div></div>
@endif
@if($purchaseOrder->terms_conditions)
<div class="row"><div class="col-12"><div class="card mb-4"><div class="card-header"><h5 class="mb-0">Terms & Conditions</h5></div><div class="card-body"><p class="mb-0">{{ $purchaseOrder->terms_conditions }}</p></div></div></div></div>
@endif

<div class="row mt-4"><div class="col-12"><div class="card"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Quick Actions</h6><small class="text-muted">Manage this purchase order</small></div><div class="d-flex gap-2">
    @if(in_array($purchaseOrder->status, ['draft', 'pending']))
        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
    @endif
    @if(in_array($purchaseOrder->status, ['draft', 'pending', 'cancelled']))
        <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this purchase order?')">
            @csrf 
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </form>
    @endif
</div></div></div></div></div>
@endsection
