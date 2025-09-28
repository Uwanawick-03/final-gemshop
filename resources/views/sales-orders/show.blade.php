@extends('layouts.app')

@section('title', 'Sales Order Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i>Sales Order Details</h4>
        <div class="small text-muted">Order #{{ $salesOrder->order_number }}</div>
    </div>
    <div>
        <a href="{{ route('sales-orders.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        @if(in_array($salesOrder->status, ['pending', 'confirmed']))
            <a href="{{ route('sales-orders.edit', $salesOrder) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        @endif
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> {{ $salesOrder->order_number }}</p>
                        <p><strong>Order Date:</strong> {{ $salesOrder->order_date?->format('M d, Y') }}</p>
                        <p><strong>Delivery Date:</strong> {{ $salesOrder->delivery_date?->format('M d, Y') ?? 'Not set' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $salesOrder->status_badge }}">{{ ucfirst($salesOrder->status) }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Customer:</strong> {{ $salesOrder->customer?->full_name }}</p>
                        <p><strong>Customer Code:</strong> {{ $salesOrder->customer?->customer_code }}</p>
                        <p><strong>Sales Assistant:</strong> {{ $salesOrder->salesAssistant?->full_name }}</p>
                        <p><strong>Created:</strong> {{ $salesOrder->created_at?->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @if($salesOrder->notes)
                    <div class="mt-3">
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $salesOrder->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Order Items</h5>
            </div>
            <div class="card-body">
                @if($salesOrder->transactionItems->count() > 0)
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
                                @foreach($salesOrder->transactionItems as $item)
                                    <tr>
                                        <td>{{ $item->item_code }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ displayAmount($item->unit_price) }}</td>
                                        <td>
                                            @if($item->discount_percentage > 0)
                                                {{ $item->discount_percentage }}% ({{ displayAmount($item->discount_amount) }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tax_percentage > 0)
                                                {{ $item->tax_percentage }}% ({{ displayAmount($item->tax_amount) }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><strong>{{ displayAmount($item->total_price) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Total Amount:</th>
                                    <th>{{ displayAmount($salesOrder->total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No items found for this order.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <!-- Status Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                @if($salesOrder->status == 'pending')
                    <form action="{{ route('sales-orders.update-status', $salesOrder) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i> Confirm Order
                        </button>
                    </form>
                @endif

                @if($salesOrder->status == 'confirmed')
                    <form action="{{ route('sales-orders.update-status', $salesOrder) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-cogs me-1"></i> Start Processing
                        </button>
                    </form>
                @endif

                @if($salesOrder->status == 'processing')
                    <form action="{{ route('sales-orders.update-status', $salesOrder) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="shipped">
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-shipping-fast me-1"></i> Mark as Shipped
                        </button>
                    </form>
                @endif

                @if($salesOrder->status == 'shipped')
                    <form action="{{ route('sales-orders.update-status', $salesOrder) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-circle me-1"></i> Mark as Delivered
                        </button>
                    </form>
                @endif

                @if(in_array($salesOrder->status, ['pending', 'confirmed', 'processing']))
                    <form action="{{ route('sales-orders.update-status', $salesOrder) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-1"></i> Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        @if($salesOrder->customer)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Customer Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $salesOrder->customer->full_name }}</p>
                    <p><strong>Code:</strong> {{ $salesOrder->customer->customer_code }}</p>
                    @if($salesOrder->customer->email)
                        <p><strong>Email:</strong> {{ $salesOrder->customer->email }}</p>
                    @endif
                    @if($salesOrder->customer->phone)
                        <p><strong>Phone:</strong> {{ $salesOrder->customer->phone }}</p>
                    @endif
                    @if($salesOrder->customer->address)
                        <p><strong>Address:</strong> {{ $salesOrder->customer->address }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Items:</span>
                    <span>{{ $salesOrder->transactionItems->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Quantity:</span>
                    <span>{{ $salesOrder->transactionItems->sum('quantity') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Amount:</strong>
                    <strong>{{ displayAmount($salesOrder->total_amount) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection