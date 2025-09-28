@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-boxes me-2"></i>Stock Report
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of stock levels, movements, and valuations</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.stocks.export-pdf', ['type' => 'summary']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.stocks.export-excel', ['type' => 'detailed']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.stocks.export-csv', ['type' => 'detailed']) }}" class="btn btn-outline-info">
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
                                Active inventory items
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

        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Alert
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['low_stock_items']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Items need restocking
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Summary Cards -->
    <div class="row mb-4">
        <!-- Out of Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['out_of_stock_items']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Items completely out of stock
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Quantity -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
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

        <!-- Categories -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Categories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['categories_count']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Product categories
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Materials
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['materials_count']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Different materials
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gem fa-2x text-gray-300"></i>
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
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.detailed') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.movements') }}" class="btn btn-info btn-block">
                                <i class="fas fa-exchange-alt me-1"></i>Stock Movements
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.valuation') }}" class="btn btn-success btn-block">
                                <i class="fas fa-calculator me-1"></i>Stock Valuation
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.stocks.export-pdf', ['type' => 'summary']) }}" class="btn btn-danger btn-block">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Stock Alerts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Low Stock Items -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>Low Stock Items
                            </h6>
                            @if($lowStockItems->count() > 0)
                                @foreach($lowStockItems->take(5) as $item)
                                <div class="alert alert-warning alert-sm mb-2">
                                    <strong>{{ $item->name }}</strong><br>
                                    <small>{{ $item->item_code }} - Current: {{ $item->current_stock }}, Min: {{ $item->minimum_stock }}</small>
                                </div>
                                @endforeach
                                @if($lowStockItems->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.stocks.detailed', ['stock_status' => 'low_stock']) }}" class="btn btn-sm btn-outline-warning">
                                        View All {{ $lowStockItems->count() }} Low Stock Items
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No low stock items found!</p>
                            @endif
                        </div>

                        <!-- Out of Stock Items -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-times-circle me-1"></i>Out of Stock Items
                            </h6>
                            @if($outOfStockItems->count() > 0)
                                @foreach($outOfStockItems->take(5) as $item)
                                <div class="alert alert-danger alert-sm mb-2">
                                    <strong>{{ $item->name }}</strong><br>
                                    <small>{{ $item->item_code }} - {{ $item->category }}</small>
                                </div>
                                @endforeach
                                @if($outOfStockItems->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.stocks.detailed', ['stock_status' => 'out_of_stock']) }}" class="btn btn-sm btn-outline-danger">
                                        View All {{ $outOfStockItems->count() }} Out of Stock Items
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No out of stock items!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Stock Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Stock by Category -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-tags me-1"></i>Stock by Category
                            </h6>
                            @if($stockByCategory->count() > 0)
                                @foreach($stockByCategory->take(5) as $category)
                                <div class="alert alert-primary alert-sm mb-2">
                                    <strong>{{ $category->category }}</strong><br>
                                    <small>{{ $category->item_count }} items - Total Stock: {{ number_format($category->total_stock) }}</small>
                                </div>
                                @endforeach
                                @if($stockByCategory->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.stocks.detailed') }}" class="btn btn-sm btn-outline-primary">
                                        View All Categories
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No category data available</p>
                            @endif
                        </div>

                        <!-- Stock by Material -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-gem me-1"></i>Stock by Material
                            </h6>
                            @if($stockByMaterial->count() > 0)
                                @foreach($stockByMaterial->take(5) as $material)
                                <div class="alert alert-info alert-sm mb-2">
                                    <strong>{{ $material->material }}</strong><br>
                                    <small>{{ $material->item_count }} items - Total Stock: {{ number_format($material->total_stock) }}</small>
                                </div>
                                @endforeach
                                @if($stockByMaterial->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reports.stocks.detailed') }}" class="btn btn-sm btn-outline-info">
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

    <!-- High Value Items -->
    @if(isset($stockValueAnalysis['high_value_items']) && $stockValueAnalysis['high_value_items']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-gem me-2"></i>High Value Items
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <td>Rs {{ number_format($item->cost_price, 2) }}</td>
                                    <td>
                                        <strong>Rs {{ number_format($item->current_stock * $item->cost_price, 2) }}</strong>
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
