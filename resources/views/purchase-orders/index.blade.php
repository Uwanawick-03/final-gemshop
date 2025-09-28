@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-shopping-cart me-2"></i>Purchase Orders</h2>
        <p class="text-muted mb-0">Manage and track all purchase orders</p>
    </div>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create New PO
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $purchaseOrders->total() }}</h4>
                        <p class="card-text">Total POs</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $purchaseOrders->where('status', 'pending')->count() }}</h4>
                        <p class="card-text">Pending</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $purchaseOrders->where('status', 'completed')->count() }}</h4>
                        <p class="card-text">Completed</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ displayAmount($purchaseOrders->sum('total_amount')) }}</h4>
                        <p class="card-text">Total Value</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-search me-2"></i>Search & Filter
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('purchase-orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="PO number, supplier name...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="from" name="from" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label for="to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="to" name="to" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'from', 'to']))
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-list me-2"></i>Purchase Orders List
        </h6>
    </div>
    <div class="card-body">
        @if($purchaseOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected Delivery</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                            <tr>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $po->po_number }}</strong>
                                        @if($po->notes)
                                            <br><small class="text-muted">{{ Str::limit($po->notes, 30) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $po->supplier?->company_name ?? '—' }}</div>
                                        @if($po->supplier?->contact_person)
                                            <small class="text-muted">{{ $po->supplier->contact_person }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $po->order_date?->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $po->order_date?->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($po->expected_delivery_date)
                                        <div>
                                            {{ $po->expected_delivery_date->format('M d, Y') }}
                                            @if($po->expected_delivery_date->isPast() && $po->status !== 'completed')
                                                <br><small class="text-danger">Overdue</small>
                                            @elseif($po->expected_delivery_date->diffInDays(now()) <= 3 && $po->status !== 'completed')
                                                <br><small class="text-warning">Due Soon</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'partially_received' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$po->status] ?? 'secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $po->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $itemCount = $po->transactionItems()->count();
                                    @endphp
                                    <span class="badge bg-info">{{ $itemCount }} items</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ displayAmount($po->total_amount) }}</div>
                                    @if($po->currency_id && $po->currency)
                                        <small class="text-muted">{{ $po->currency->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('purchase-orders.show', $po) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($po->status, ['draft', 'pending']))
                                            <a href="{{ route('purchase-orders.edit', $po) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(in_array($po->status, ['draft', 'pending', 'cancelled']))
                                            <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this purchase order?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
            <div class="d-flex justify-content-center mt-4">
                {{ $purchaseOrders->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Purchase Orders Found</h5>
                <p class="text-muted">Get started by creating your first purchase order.</p>
                <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Purchase Order
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
@if($purchaseOrders->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2 text-warning"></i>Pending Orders
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $pendingOrders = $purchaseOrders->where('status', 'pending')->take(3);
                    @endphp
                    
                    @if($pendingOrders->count() > 0)
                        @foreach($pendingOrders as $po)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold">{{ $po->po_number }}</div>
                                    <small class="text-muted">{{ $po->supplier?->company_name }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">{{ displayAmount($po->total_amount) }}</div>
                                    <small class="text-muted">{{ $po->order_date?->format('M d') }}</small>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No pending orders</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-primary mb-1">{{ $purchaseOrders->where('status', 'draft')->count() }}</h5>
                            <small class="text-muted">Draft</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning mb-1">{{ $purchaseOrders->where('status', 'pending')->count() }}</h5>
                            <small class="text-muted">Pending</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1">{{ $purchaseOrders->where('status', 'completed')->count() }}</h5>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection



