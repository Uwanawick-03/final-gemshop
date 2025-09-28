@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.sales.index') }}">Sales Report</a></li>
                        <li class="breadcrumb-item active">Customer Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-users me-2"></i>Customer Sales Report
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.sales.customers') }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Customer name, company, or email">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('reports.sales.customers') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Customer Sales Report ({{ $customers->total() }} customers)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.sales.export-pdf', ['type' => 'customers']) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.sales.export-excel', ['type' => 'customers']) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.sales.export-csv', ['type' => 'customers']) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Customer</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Orders</th>
                                    <th>Total Sales</th>
                                    <th>Average Order</th>
                                    <th>Last Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->full_name }}</strong>
                                            @if($customer->is_active)
                                                <span class="badge bg-success ms-1">Active</span>
                                            @else
                                                <span class="badge bg-secondary ms-1">Inactive</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($customer->company_name)
                                            <strong>{{ $customer->company_name }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                                {{ $customer->email }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($customer->phone)
                                            <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                                {{ $customer->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $customer->total_invoices }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rs {{ number_format($customer->total_sales, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($customer->total_invoices > 0 ? $customer->total_sales / $customer->total_invoices : 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $lastOrder = $customer->invoices()->latest()->first();
                                        @endphp
                                        @if($lastOrder)
                                            <div>
                                                {{ $lastOrder->invoice_date->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $lastOrder->invoice_number }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">No orders</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>No customers found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($customers->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $customers->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ number_format($customers->sum('total_invoices')) }}</h4>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">Rs {{ number_format($customers->sum('total_sales'), 2) }}</h4>
                                <p class="text-muted mb-0">Total Sales Value</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">Rs {{ number_format($customers->avg('total_sales'), 2) }}</h4>
                                <p class="text-muted mb-0">Average Customer Value</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ number_format($customers->avg('total_invoices'), 1) }}</h4>
                                <p class="text-muted mb-0">Average Orders per Customer</p>
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
// Auto-submit form on search
document.getElementById('search').addEventListener('input', function() {
    // Debounce the search
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        this.form.submit();
    }, 500);
});
</script>
@endpush
