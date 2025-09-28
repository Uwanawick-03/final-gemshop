@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of your gem shop operations and performance</p>
        </div>
        <div>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('l, F j, Y') }}
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
                                {{ $totalItems ?? 0 }}
                            </div>
                            <div class="text-xs text-muted">
                                Active inventory items
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gem fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalCustomers ?? 0 }}
                            </div>
                            <div class="text-xs text-muted">
                                Registered customers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Suppliers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalSuppliers ?? 0 }}
                            </div>
                            <div class="text-xs text-muted">
                                Active suppliers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Alert
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $lowStockItems ?? 0 }}
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
                            <a href="{{ route('items.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus me-1"></i>Add New Item
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('customers.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus me-1"></i>Add Customer
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('purchase-orders.create') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-shopping-cart me-1"></i>Create Purchase Order
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.create') }}" class="btn btn-info btn-block">
                                <i class="fas fa-file-invoice me-1"></i>Create Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Reports & Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Stock Reports -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Stock Reports
                                            </div>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                Monitor inventory levels, stock movements, and valuations
                                            </div>
                                            <div class="text-xs text-muted">
                                                Detailed analysis and reporting
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.stocks.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-chart-line me-1"></i>View Dashboard
                                        </a>
                                        <div class="btn-group mt-2" role="group">
                                            <a href="{{ route('reports.stocks.detailed') }}" class="btn btn-outline-primary btn-sm">Detailed</a>
                                            <a href="{{ route('reports.stocks.movements') }}" class="btn btn-outline-primary btn-sm">Movements</a>
                                            <a href="{{ route('reports.stocks.valuation') }}" class="btn btn-outline-primary btn-sm">Valuation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Reports -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-left-success shadow h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Sales Reports
                                            </div>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                Analyze sales performance, customer insights, and revenue trends
                                            </div>
                                            <div class="text-xs text-muted">
                                                Performance analytics and insights
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.sales.index') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-chart-bar me-1"></i>View Dashboard
                                        </a>
                                        <div class="btn-group mt-2" role="group">
                                            <a href="{{ route('reports.sales.detailed') }}" class="btn btn-outline-success btn-sm">Detailed</a>
                                            <a href="{{ route('reports.sales.analytics') }}" class="btn btn-outline-success btn-sm">Analytics</a>
                                            <a href="{{ route('reports.sales.customers') }}" class="btn btn-outline-success btn-sm">Customers</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Reports -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-left-info shadow h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Inventory Reports
                                            </div>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                Track inventory adjustments, transfers, and comprehensive analysis
                                            </div>
                                            <div class="text-xs text-muted">
                                                Complete inventory management
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.inventory.index') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-chart-pie me-1"></i>View Dashboard
                                        </a>
                                        <div class="btn-group mt-2" role="group">
                                            <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-outline-info btn-sm">Detailed</a>
                                            <a href="{{ route('reports.inventory.movements') }}" class="btn btn-outline-info btn-sm">Movements</a>
                                            <a href="{{ route('reports.inventory.valuation') }}" class="btn btn-outline-info btn-sm">Valuation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <i class="fas fa-clock me-2"></i>Recent Sales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales ?? [] as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->customer->full_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ $sale->created_at->format('M j, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent sales</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockItemsList ?? [] as $item)
                                <tr>
                                    <td>{{ $item->item_code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->stock_status_color }}">
                                            {{ $item->current_stock }}
                                        </span>
                                    </td>
                                    <td>{{ $item->minimum_stock }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">All items are well stocked</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Monthly Sales Overview
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Sales Amount',
                data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000],
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush


