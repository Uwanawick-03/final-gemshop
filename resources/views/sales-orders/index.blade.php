@extends('layouts.app')

@section('title', 'Sales Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i>Sales Orders</h4>
        <div class="small text-muted">Manage your sales orders</div>
    </div>
    <a href="{{ route('sales-orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Sales Order
    </a>
</div>

<!-- Status Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-warning status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Pending Orders</div>
                        <div class="status-number text-warning">{{ $statusCounts['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'pending']) }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Pending
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-info status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Confirmed Orders</div>
                        <div class="status-number text-info">{{ $statusCounts['confirmed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'confirmed']) }}" class="text-info text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Confirmed
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-primary status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-cogs fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Processing</div>
                        <div class="status-number text-primary">{{ $statusCounts['processing'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'processing']) }}" class="text-primary text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Processing
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-success status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-shipping-fast fa-2x text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Shipped/Delivered</div>
                        <div class="status-number text-success">{{ ($statusCounts['shipped'] ?? 0) + ($statusCounts['delivered'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'shipped']) }}" class="text-success text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Shipped
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Additional Status Cards Row -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-secondary status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-check-double fa-2x text-secondary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Delivered Orders</div>
                        <div class="status-number text-secondary">{{ $statusCounts['delivered'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-secondary bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'delivered']) }}" class="text-secondary text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Delivered
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-danger status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Cancelled Orders</div>
                        <div class="status-number text-danger">{{ $statusCounts['cancelled'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-danger bg-opacity-10">
                <a href="{{ route('sales-orders.index', ['status' => 'cancelled']) }}" class="text-danger text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View Cancelled
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-dark status-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 status-icon">
                        <i class="fas fa-chart-bar fa-2x text-dark"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted status-label">Total Orders</div>
                        <div class="status-number text-dark">{{ $totalOrders ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-dark bg-opacity-10">
                <a href="{{ route('sales-orders.index') }}" class="text-dark text-decoration-none small">
                    <i class="fas fa-eye me-1"></i> View All Orders
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Order number, customer, status...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('sales-orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sales Orders Table -->
<div class="card">
    <div class="card-body">
        @if($salesOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Sales Assistant</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrders as $salesOrder)
                            <tr>
                                <td>
                                    <a href="{{ route('sales-orders.show', $salesOrder) }}" class="text-decoration-none">
                                        <strong>{{ $salesOrder->order_number }}</strong>
                                    </a>
                                </td>
                                <td>
                                    {{ $salesOrder->customer?->full_name }}
                                    @if($salesOrder->customer?->customer_code)
                                        <small class="text-muted d-block">{{ $salesOrder->customer->customer_code }}</small>
                                    @endif
                                </td>
                                <td>{{ $salesOrder->salesAssistant?->full_name }}</td>
                                <td>{{ $salesOrder->order_date?->format('M d, Y') }}</td>
                                <td>
                                    @if($salesOrder->delivery_date)
                                        {{ $salesOrder->delivery_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $salesOrder->status_badge }}">
                                        {{ ucfirst($salesOrder->status) }}
                                    </span>
                                </td>
                                <td><strong>{{ displayAmount($salesOrder->total_amount) }}</strong></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('sales-orders.show', $salesOrder) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($salesOrder->status, ['pending', 'confirmed']))
                                            <a href="{{ route('sales-orders.edit', $salesOrder) }}" 
                                               class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(in_array($salesOrder->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                                            <a href="{{ route('invoices.create-from-sales-order', $salesOrder) }}" 
                                               class="btn btn-outline-success" title="Create Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        @endif
                                        @if(in_array($salesOrder->status, ['pending', 'confirmed', 'processing']))
                                            <form action="{{ route('sales-orders.destroy', $salesOrder) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Showing {{ $salesOrders->firstItem() }} to {{ $salesOrders->lastItem() }} 
                    of {{ $salesOrders->total() }} results
                </div>
                {{ $salesOrders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No sales orders found</h5>
                <p class="text-muted">Create your first sales order to get started.</p>
                <a href="{{ route('sales-orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create Sales Order
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.status-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.status-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.border-warning .status-icon {
    background-color: rgba(255, 193, 7, 0.1);
}

.border-info .status-icon {
    background-color: rgba(13, 202, 240, 0.1);
}

.border-primary .status-icon {
    background-color: rgba(13, 110, 253, 0.1);
}

.border-success .status-icon {
    background-color: rgba(25, 135, 84, 0.1);
}

.border-secondary .status-icon {
    background-color: rgba(108, 117, 125, 0.1);
}

.border-danger .status-icon {
    background-color: rgba(220, 53, 69, 0.1);
}

.border-dark .status-icon {
    background-color: rgba(33, 37, 41, 0.1);
}

.status-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .status-number {
        font-size: 1.5rem;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
    }
}
</style>
@endsection