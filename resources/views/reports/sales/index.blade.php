@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line me-2"></i>Sales Report
            </h1>
            <p class="text-muted mb-0">Comprehensive overview of sales performance, customer insights, and revenue trends</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.sales.export-pdf', ['type' => 'summary']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.sales.export-excel', ['type' => 'detailed']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.sales.export-csv', ['type' => 'detailed']) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Sales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['total_sales'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Total revenue generated
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invoices -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['total_invoices']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Total invoices issued
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Amount -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Paid Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['paid_amount'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Amount received
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Amount -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['overdue_amount'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Outstanding payments
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
        <!-- This Month Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['this_month_sales'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Current month sales
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $summary['growth_percentage'] >= 0 ? 'success' : 'danger' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $summary['growth_percentage'] >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                                Growth
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $summary['growth_percentage'] >= 0 ? '+' : '' }}{{ number_format($summary['growth_percentage'], 1) }}%
                            </div>
                            <div class="text-xs text-muted">
                                Sales growth rate
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $summary['growth_percentage'] >= 0 ? 'arrow-up' : 'arrow-down' }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Invoice Value -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg Invoice Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($summary['average_invoice_value'], 2) }}
                            </div>
                            <div class="text-xs text-muted">
                                Average order value
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($summary['overdue_invoices']) }}
                            </div>
                            <div class="text-xs text-muted">
                                Invoices past due
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <a href="{{ route('reports.sales.detailed') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.sales.analytics') }}" class="btn btn-info btn-block">
                                <i class="fas fa-chart-line me-1"></i>Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.sales.customers') }}" class="btn btn-success btn-block">
                                <i class="fas fa-users me-1"></i>Customer Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.sales.products') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-boxes me-1"></i>Product Report
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
                        <i class="fas fa-clock me-2"></i>Recent Sales
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentSales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $sale->invoice_number }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $sale->invoice_date->format('M d, Y') }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $sale->customer->full_name ?? 'N/A' }}</td>
                                        <td>
                                            <strong>Rs {{ number_format($sale->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $sale->status_color }}">
                                                {{ $sale->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <p>No recent sales found!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Top Customers
                    </h6>
                </div>
                <div class="card-body">
                    @if($topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Orders</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCustomers as $customer)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $customer->full_name }}</strong>
                                                @if($customer->company_name)
                                                    <br>
                                                    <small class="text-muted">{{ $customer->company_name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $customer->total_orders }}</td>
                                        <td>
                                            <strong>Rs {{ number_format($customer->total_sales, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>No customer data available!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Sales Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sales by Status -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-info-circle me-1"></i>Sales by Status
                            </h6>
                            @if($salesByStatus->count() > 0)
                                @foreach($salesByStatus as $status)
                                <div class="alert alert-{{ $status->status == 'paid' ? 'success' : ($status->status == 'overdue' ? 'danger' : 'info') }} alert-sm mb-2">
                                    <strong>{{ ucfirst($status->status) }}</strong><br>
                                    <small>{{ $status->count }} invoices - Rs {{ number_format($status->total, 2) }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No status data available</p>
                            @endif
                        </div>

                        <!-- Sales by Payment Method -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-credit-card me-1"></i>Sales by Payment Method
                            </h6>
                            @if($salesByPaymentMethod->count() > 0)
                                @foreach($salesByPaymentMethod as $method)
                                <div class="alert alert-{{ $method->payment_method == 'cash' ? 'success' : ($method->payment_method == 'card' ? 'info' : 'warning') }} alert-sm mb-2">
                                    <strong>{{ ucfirst($method->payment_method) }}</strong><br>
                                    <small>{{ $method->count }} transactions - Rs {{ number_format($method->total, 2) }}</small>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No payment method data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Items -->
    @if($topSellingItems->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star me-2"></i>Top Selling Items
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Sales Count</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSellingItems as $item)
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
                                        <span class="badge bg-primary">{{ $item->total_sales }}</span>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($item->total_revenue, 2) }}</strong>
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

    <!-- Sales Performance -->
    @if($salesPerformance->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Sales Performance by Assistant
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Assistant</th>
                                    <th>Total Orders</th>
                                    <th>Total Sales</th>
                                    <th>Average Order Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesPerformance as $assistant)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $assistant->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $assistant->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $assistant->total_orders }}</td>
                                    <td>
                                        <strong>Rs {{ number_format($assistant->total_sales, 2) }}</strong>
                                    </td>
                                    <td>
                                        Rs {{ number_format($assistant->total_orders > 0 ? $assistant->total_sales / $assistant->total_orders : 0, 2) }}
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
