@extends('layouts.app')

@section('title', 'MTCs Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list me-2"></i>Material Transfer Certificates (MTCs) Report
            </h1>
            <p class="text-muted mb-0">Detailed report of Material Transfer Certificates and their status</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'mtcs']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'mtcs']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'mtcs']) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.workshop.mtcs') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select" id="customer_id" name="customer_id">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sales_assistant_id" class="form-label">Sales Assistant</label>
                        <select class="form-select" id="sales_assistant_id" name="sales_assistant_id">
                            <option value="">All Sales Assistants</option>
                            @foreach($salesAssistants as $assistant)
                            <option value="{{ $assistant->id }}" {{ request('sales_assistant_id') == $assistant->id ? 'selected' : '' }}>
                                {{ $assistant->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="expiring_soon" name="expiring_soon" value="1" {{ request('expiring_soon') ? 'checked' : '' }}>
                            <label class="form-check-label" for="expiring_soon">
                                Expiring Soon (30 days)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="expired" name="expired" value="1" {{ request('expired') ? 'checked' : '' }}>
                            <label class="form-check-label" for="expired">
                                Expired
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.workshop.mtcs') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total MTCs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mtcs->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mtcs->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Expiring Soon
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mtcs->filter(function($mtc) { return $mtc->is_expiring_soon; })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Expired
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mtcs->where('status', 'expired')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-dollar-sign me-2"></i>Financial Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h3 class="text-info">${{ number_format($mtcs->sum('purchase_price'), 2) }}</h3>
                                <p class="text-muted mb-0">Total Purchase Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h3 class="text-success">${{ number_format($mtcs->sum('selling_price'), 2) }}</h3>
                                <p class="text-muted mb-0">Total Selling Value</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                @php
                                    $totalProfit = $mtcs->sum('selling_price') - $mtcs->sum('purchase_price');
                                @endphp
                                <h3 class="text-{{ $totalProfit >= 0 ? 'success' : 'danger' }}">${{ number_format($totalProfit, 2) }}</h3>
                                <p class="text-muted mb-0">Total Profit/Loss</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MTCs Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Material Transfer Certificates
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>MTC Number</th>
                            <th>Item</th>
                            <th>Customer</th>
                            <th>Sales Assistant</th>
                            <th>Status</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Days Until Expiry</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Profit/Loss</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mtcs as $mtc)
                        <tr class="{{ $mtc->is_expired ? 'table-danger' : ($mtc->is_expiring_soon ? 'table-warning' : '') }}">
                            <td>
                                <strong>{{ $mtc->mtc_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $mtc->item) }}" class="text-decoration-none">
                                    {{ $mtc->item->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $mtc->customer->full_name ?? 'N/A' }}</td>
                            <td>{{ $mtc->salesAssistant->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $mtc->status === 'active' ? 'success' : ($mtc->status === 'expired' ? 'warning' : ($mtc->status === 'used' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($mtc->status) }}
                                </span>
                            </td>
                            <td>{{ $mtc->issue_date->format('M d, Y') }}</td>
                            <td>{{ $mtc->expiry_date->format('M d, Y') }}</td>
                            <td>
                                @if($mtc->days_until_expiry !== null)
                                    @if($mtc->days_until_expiry < 0)
                                        <span class="badge bg-danger">Expired {{ abs($mtc->days_until_expiry) }} days ago</span>
                                    @elseif($mtc->days_until_expiry <= 30)
                                        <span class="badge bg-warning">{{ $mtc->days_until_expiry }} days</span>
                                    @else
                                        <span class="badge bg-success">{{ $mtc->days_until_expiry }} days</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>${{ number_format($mtc->purchase_price, 2) }}</td>
                            <td>${{ number_format($mtc->selling_price, 2) }}</td>
                            <td>
                                @php
                                    $profit = $mtc->selling_price - $mtc->purchase_price;
                                @endphp
                                <span class="badge bg-{{ $profit >= 0 ? 'success' : 'danger' }}">
                                    ${{ number_format($profit, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('mtcs.show', $mtc) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mtcs.edit', $mtc) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No MTCs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($mtcs->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $mtcs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
