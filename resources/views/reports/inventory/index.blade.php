@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventory Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-warehouse me-2"></i>Inventory Report Dashboard
                </h4>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Items</p>
                            <h4 class="mb-0">{{ number_format($summary['total_items']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-primary font-size-18">
                                    <i class="fas fa-boxes"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Active Items</p>
                            <h4 class="mb-0">{{ number_format($summary['active_items']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-success font-size-18">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Stock Value</p>
                            <h4 class="mb-0">${{ number_format($summary['total_stock_value'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Quantity</p>
                            <h4 class="mb-0">{{ number_format($summary['total_quantity']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-cubes"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Low Stock Items</p>
                            <h4 class="mb-0 text-warning">{{ number_format($summary['low_stock_items']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Out of Stock</p>
                            <h4 class="mb-0 text-danger">{{ number_format($summary['out_of_stock_items']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-danger font-size-18">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Overstock Items</p>
                            <h4 class="mb-0 text-info">{{ number_format($summary['overstock_items']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.movements') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-exchange-alt me-1"></i>Movements
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.valuation') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calculator me-1"></i>Valuation
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.adjustments') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-balance-scale me-1"></i>Adjustments
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.transfers') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-truck me-1"></i>Transfers
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-dark w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('reports.inventory.export-pdf', ['type' => 'summary']) }}">Export PDF</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.inventory.export-excel', ['type' => 'detailed']) }}">Export Excel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.inventory.export-csv', ['type' => 'detailed']) }}">Export CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Low Stock Items -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Low Stock Items</h5>
                </div>
                <div class="card-body">
                    @if($inventoryAlerts['low_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Current</th>
                                        <th>Min</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryAlerts['low_stock'] as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->item_code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $item->current_stock }}</span>
                                        </td>
                                        <td>{{ $item->minimum_stock }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->stock_status_color }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                            <p>No low stock items found!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Out of Stock Items -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Out of Stock Items</h5>
                </div>
                <div class="card-body">
                    @if($inventoryAlerts['out_of_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryAlerts['out_of_stock'] as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->item_code }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $item->category }}</td>
                                        <td>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                            <p>No out of stock items!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Inventory by Category -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Inventory by Category</h5>
                </div>
                <div class="card-body">
                    @if($inventoryByCategory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Items</th>
                                        <th>Total Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryByCategory as $category)
                                    <tr>
                                        <td><strong>{{ $category->category }}</strong></td>
                                        <td>{{ $category->item_count }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ number_format($category->total_stock) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>No category data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Inventory by Material -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Inventory by Material</h5>
                </div>
                <div class="card-body">
                    @if($inventoryByMaterial->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Items</th>
                                        <th>Total Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryByMaterial as $material)
                                    <tr>
                                        <td><strong>{{ $material->material }}</strong></td>
                                        <td>{{ $material->item_count }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format($material->total_stock) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>No material data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustments Summary -->
    @if($stockAdjustmentsSummary['recent_adjustments']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Stock Adjustments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Adjustment #</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockAdjustmentsSummary['recent_adjustments'] as $adjustment)
                                <tr>
                                    <td>
                                        <strong>{{ $adjustment->adjustment_number }}</strong>
                                    </td>
                                        <td>{{ isset($adjustment->adjustment_date) ? $adjustment->adjustment_date->format('M d, Y') : $adjustment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if(isset($adjustment->type))
                                                <span class="badge bg-{{ $adjustment->type == 'increase' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($adjustment->type) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $adjustment->total_items ?? 0 }}</td>
                                        <td>
                                            @if(isset($adjustment->status))
                                                <span class="badge bg-{{ $adjustment->status_color }}">
                                                    {{ $adjustment->status_label }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                    <td>{{ $adjustment->createdBy->name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Item Transfers Summary -->
    @if($itemTransfersSummary['recent_transfers']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Item Transfers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transfer #</th>
                                    <th>Item</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemTransfersSummary['recent_transfers'] as $transfer)
                                <tr>
                                    <td>
                                        <strong>{{ $transfer->reference_number }}</strong>
                                    </td>
                                    <td>{{ $transfer->item->name }}</td>
                                    <td>{{ $transfer->from_location }}</td>
                                    <td>{{ $transfer->to_location }}</td>
                                    <td>{{ $transfer->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status_color }}">
                                            {{ $transfer->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Inventory Valuation Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Inventory Valuation Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary">${{ number_format($inventoryValuation['cost_value'], 2) }}</h4>
                                <p class="text-muted mb-0">Cost Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-success">${{ number_format($inventoryValuation['selling_value'], 2) }}</h4>
                                <p class="text-muted mb-0">Selling Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info">${{ number_format($inventoryValuation['wholesale_value'], 2) }}</h4>
                                <p class="text-muted mb-0">Wholesale Value</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>
@endpush
