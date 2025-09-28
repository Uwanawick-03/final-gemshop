@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-warehouse me-2"></i>Inventory Report
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of inventory adjustments, transfers, and comprehensive analysis</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.inventory.export-pdf', ['type' => 'summary']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.inventory.export-excel', ['type' => 'detailed']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.inventory.export-csv', ['type' => 'detailed']) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Items -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['total_items']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Total inventory items
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Items -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['active_items']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Currently available items
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Stock Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['total_stock_value'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Total inventory value
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Quantity -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Quantity
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['total_quantity']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Total units in inventory
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cubes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Inventory Alerts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Low Stock Items -->
                        <div class="col-md-4 mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Low Stock Items
                            </h6>
                            <div class="alert alert-warning alert-sm mb-2">
                                <strong>{{ number_format($summary['low_stock_items']) }}</strong><br>
                                <small>Items need restocking</small>
                            </div>
                        </div>

                        <!-- Out of Stock Items -->
                        <div class="col-md-4 mb-3">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-times-circle me-1"></i>Out of Stock Items
                            </h6>
                            <div class="alert alert-danger alert-sm mb-2">
                                <strong>{{ number_format($summary['out_of_stock_items']) }}</strong><br>
                                <small>Items completely out of stock</small>
                            </div>
                        </div>

                        <!-- Overstock Items -->
                        <div class="col-md-4 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-arrow-up me-1"></i>Overstock Items
                            </h6>
                            <div class="alert alert-info alert-sm mb-2">
                                <strong>{{ number_format($summary['overstock_items']) }}</strong><br>
                                <small>Items with excess stock</small>
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.movements') }}" class="btn btn-info btn-block">
                                <i class="fas fa-exchange-alt me-1"></i>Movements
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.valuation') }}" class="btn btn-success btn-block">
                                <i class="fas fa-calculator me-1"></i>Valuation
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.adjustments') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-balance-scale me-1"></i>Adjustments
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.transfers') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-truck me-1"></i>Transfers
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.inventory.export-pdf', ['type' => 'summary']) }}" class="btn btn-danger btn-block">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Items
                    </h6>
                </div>
                <div class="card-body">
                    @if($inventoryAlerts['low_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-times-circle me-2"></i>Out of Stock Items
                    </h6>
                </div>
                <div class="card-body">
                    @if($inventoryAlerts['out_of_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
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

    <!-- Inventory Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Inventory Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Inventory by Category -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-tags me-1"></i>Inventory by Category
                            </h6>
                            @if($inventoryByCategory->count() > 0)
                                @foreach($inventoryByCategory->take(5) as $category)
                                <div class="alert alert-primary alert-sm mb-2">
                                    <strong>{{ $category->category }}</strong><br>
                                    <small>{{ $category->item_count }} items - Total Stock: {{ number_format($category->total_stock) }}</small>
                                </div>
                                @endforeach
                                @if($inventoryByCategory->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-sm btn-outline-primary">
                                        View All Categories
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No category data available</p>
                            @endif
                        </div>

                        <!-- Inventory by Material -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-gem me-1"></i>Inventory by Material
                            </h6>
                            @if($inventoryByMaterial->count() > 0)
                                @foreach($inventoryByMaterial->take(5) as $material)
                                <div class="alert alert-info alert-sm mb-2">
                                    <strong>{{ $material->material }}</strong><br>
                                    <small>{{ $material->item_count }} items - Total Stock: {{ number_format($material->total_stock) }}</small>
                                </div>
                                @endforeach
                                @if($inventoryByMaterial->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-sm btn-outline-info">
                                        View All Materials
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No material data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustments Summary -->
    @if($stockAdjustmentsSummary['recent_adjustments']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-balance-scale me-2"></i>Recent Stock Adjustments
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <td>{{ $adjustment->createdBy ? $adjustment->createdBy->name : 'N/A' }}</td>
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-truck me-2"></i>Recent Item Transfers
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <td>{{ $transfer->item ? $transfer->item->name : 'N/A' }}</td>
                                    <td>{{ $transfer->from_location }}</td>
                                    <td>{{ $transfer->to_location }}</td>
                                    <td>{{ $transfer->quantity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status_color ?? 'secondary' }}">
                                            {{ $transfer->status_label ?? ucfirst($transfer->status ?? 'Unknown') }}
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calculator me-2"></i>Inventory Valuation Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary">Rs {{ number_format($inventoryValuation['cost_value'], 2) }}</h4>
                                <p class="text-muted mb-0">Cost Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-success">Rs {{ number_format($inventoryValuation['selling_value'], 2) }}</h4>
                                <p class="text-muted mb-0">Selling Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info">Rs {{ number_format($inventoryValuation['wholesale_value'], 2) }}</h4>
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
