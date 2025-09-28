@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Stocks Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-boxes me-2"></i>Stocks Report Dashboard
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
    </div>

    <!-- Additional Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
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

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Quantity</p>
                            <h4 class="mb-0">{{ number_format($summary['total_quantity']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-secondary-subtle">
                                <span class="avatar-title rounded-circle bg-secondary text-secondary font-size-18">
                                    <i class="fas fa-cubes"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Categories</p>
                            <h4 class="mb-0">{{ number_format($summary['categories_count']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-primary font-size-18">
                                    <i class="fas fa-tags"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Materials</p>
                            <h4 class="mb-0">{{ number_format($summary['materials_count']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-gem"></i>
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
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.detailed') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.movements') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-exchange-alt me-1"></i>Stock Movements
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.valuation') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-calculator me-1"></i>Stock Valuation
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('reports.stocks.export-pdf', ['type' => 'summary']) }}">Export PDF</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.stocks.export-excel', ['type' => 'detailed']) }}">Export Excel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.stocks.export-csv', ['type' => 'detailed']) }}">Export CSV</a></li>
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
                    @if($lowStockItems->count() > 0)
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
                                    @foreach($lowStockItems as $item)
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
                        @if($lowStockItems->count() >= 20)
                        <div class="text-center">
                            <a href="{{ route('reports.stocks.detailed', ['stock_status' => 'low_stock']) }}" class="btn btn-sm btn-outline-primary">
                                View All Low Stock Items
                            </a>
                        </div>
                        @endif
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
                    @if($outOfStockItems->count() > 0)
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
                                    @foreach($outOfStockItems as $item)
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
        <!-- Stock by Category -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Stock by Category</h5>
                </div>
                <div class="card-body">
                    @if($stockByCategory->count() > 0)
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
                                    @foreach($stockByCategory as $category)
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

        <!-- Stock by Material -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Stock by Material</h5>
                </div>
                <div class="card-body">
                    @if($stockByMaterial->count() > 0)
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
                                    @foreach($stockByMaterial as $material)
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

    <!-- High Value Items -->
    @if(isset($stockValueAnalysis['high_value_items']) && $stockValueAnalysis['high_value_items']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">High Value Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Cost Price</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockValueAnalysis['high_value_items'] as $item)
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
                                        <span class="badge bg-{{ $item->stock_status_color }}">{{ $item->current_stock }}</span>
                                    </td>
                                    <td>${{ number_format($item->cost_price, 2) }}</td>
                                    <td>
                                        <strong>${{ number_format($item->current_stock * $item->cost_price, 2) }}</strong>
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
