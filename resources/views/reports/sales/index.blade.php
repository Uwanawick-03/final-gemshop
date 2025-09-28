@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sales Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-line me-2"></i>Sales Report Dashboard
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
                            <p class="text-truncate font-size-14 mb-2">Total Sales</p>
                            <h4 class="mb-0">${{ number_format($summary['total_sales'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-primary font-size-18">
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
                            <p class="text-truncate font-size-14 mb-2">Total Invoices</p>
                            <h4 class="mb-0">{{ number_format($summary['total_invoices']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-success font-size-18">
                                    <i class="fas fa-file-invoice"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Paid Amount</p>
                            <h4 class="mb-0 text-success">${{ number_format($summary['paid_amount'], 2) }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Overdue Amount</p>
                            <h4 class="mb-0 text-danger">${{ number_format($summary['overdue_amount'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-danger font-size-18">
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
                            <p class="text-truncate font-size-14 mb-2">This Month</p>
                            <h4 class="mb-0">${{ number_format($summary['this_month_sales'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-calendar"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Growth</p>
                            <h4 class="mb-0 {{ $summary['growth_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $summary['growth_percentage'] >= 0 ? '+' : '' }}{{ number_format($summary['growth_percentage'], 1) }}%
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-{{ $summary['growth_percentage'] >= 0 ? 'success' : 'danger' }}-subtle">
                                <span class="avatar-title rounded-circle bg-{{ $summary['growth_percentage'] >= 0 ? 'success' : 'danger' }} text-{{ $summary['growth_percentage'] >= 0 ? 'success' : 'danger' }} font-size-18">
                                    <i class="fas fa-{{ $summary['growth_percentage'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Avg Invoice Value</p>
                            <h4 class="mb-0">${{ number_format($summary['average_invoice_value'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-chart-bar"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Overdue Invoices</p>
                            <h4 class="mb-0 text-danger">{{ number_format($summary['overdue_invoices']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle">
                                <span class="avatar-title rounded-circle bg-danger text-danger font-size-18">
                                    <i class="fas fa-clock"></i>
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
                            <a href="{{ route('reports.sales.detailed') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-1"></i>Detailed Report
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.sales.analytics') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-chart-line me-1"></i>Analytics
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.sales.customers') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-users me-1"></i>Customer Report
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('reports.sales.products') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-boxes me-1"></i>Product Report
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('reports.sales.export-pdf', ['type' => 'summary']) }}">Export PDF</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.sales.export-excel', ['type' => 'detailed']) }}">Export Excel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reports.sales.export-csv', ['type' => 'detailed']) }}">Export CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Sales -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Sales</h5>
                </div>
                <div class="card-body">
                    @if($recentSales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
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
                                            <strong>${{ number_format($sale->total_amount, 2) }}</strong>
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
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Customers</h5>
                </div>
                <div class="card-body">
                    @if($topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
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
                                            <strong>${{ number_format($customer->total_sales, 2) }}</strong>
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

    <div class="row">
        <!-- Sales by Status -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales by Status</h5>
                </div>
                <div class="card-body">
                    @if($salesByStatus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByStatus as $status)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $status->status == 'paid' ? 'success' : ($status->status == 'overdue' ? 'danger' : 'info') }}">
                                                {{ ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $status->count }}</td>
                                        <td>
                                            <strong>${{ number_format($status->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>No status data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales by Payment Method -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    @if($salesByPaymentMethod->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment Method</th>
                                        <th>Count</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesByPaymentMethod as $method)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $method->payment_method == 'cash' ? 'success' : ($method->payment_method == 'card' ? 'info' : 'warning') }}">
                                                {{ ucfirst($method->payment_method) }}
                                            </span>
                                        </td>
                                        <td>{{ $method->count }}</td>
                                        <td>
                                            <strong>${{ number_format($method->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>No payment method data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Selling Items -->
    @if($topSellingItems->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Selling Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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
                                        <strong>${{ number_format($item->total_revenue, 2) }}</strong>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Performance by Assistant</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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
                                        <strong>${{ number_format($assistant->total_sales, 2) }}</strong>
                                    </td>
                                    <td>
                                        ${{ number_format($assistant->total_orders > 0 ? $assistant->total_sales / $assistant->total_orders : 0, 2) }}
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
