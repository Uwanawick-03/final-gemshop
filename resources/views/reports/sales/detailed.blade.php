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
                        <li class="breadcrumb-item active">Detailed Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-list me-2"></i>Detailed Sales Report
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
                    <form method="GET" action="{{ route('reports.sales.detailed') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Invoice number or customer name">
                            </div>
                            <div class="col-md-2 mb-3">
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
                            <div class="col-md-2 mb-3">
                                <label for="sales_assistant_id" class="form-label">Assistant</label>
                                <select class="form-select" id="sales_assistant_id" name="sales_assistant_id">
                                    <option value="">All Assistants</option>
                                    @foreach($salesAssistants as $assistant)
                                        <option value="{{ $assistant->id }}" {{ request('sales_assistant_id') == $assistant->id ? 'selected' : '' }}>
                                            {{ $assistant->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="">All Methods</option>
                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="credit" {{ request('payment_method') == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('reports.sales.detailed') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Sales Invoices ({{ $invoices->total() }} total)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.sales.export-pdf', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.sales.export-excel', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.sales.export-csv', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Assistant</th>
                                    <th>Subtotal</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                            @if($invoice->sales_order_id)
                                                <br>
                                                <small class="text-muted">SO: {{ $invoice->salesOrder->order_number ?? 'N/A' }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $invoice->customer->full_name ?? 'N/A' }}</strong>
                                            @if($invoice->customer->company_name)
                                                <br>
                                                <small class="text-muted">{{ $invoice->customer->company_name }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $invoice->invoice_date->format('M d, Y') }}
                                            @if($invoice->due_date)
                                                <br>
                                                <small class="text-muted">Due: {{ $invoice->due_date->format('M d, Y') }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $invoice->salesAssistant->full_name ?? 'N/A' }}</td>
                                    <td class="text-right">Rs {{ number_format($invoice->subtotal, 2) }}</td>
                                    <td class="text-right">Rs {{ number_format($invoice->tax_amount, 2) }}</td>
                                    <td class="text-right">Rs {{ number_format($invoice->discount_amount, 2) }}</td>
                                    <td class="text-right">
                                        <strong>Rs {{ number_format($invoice->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status_color }}">
                                            {{ $invoice->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($invoice->payment_method)
                                            <span class="badge bg-{{ $invoice->payment_method_color }}">
                                                {{ $invoice->payment_method_label }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                            <p>No invoices found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($invoices->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $invoices->appends(request()->query())->links() }}
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
                                <h4 class="text-primary">{{ number_format($invoices->sum('total_amount')) }}</h4>
                                <p class="text-muted mb-0">Total Sales Value</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">Rs {{ number_format($invoices->where('status', 'paid')->sum('total_amount'), 2) }}</h4>
                                <p class="text-muted mb-0">Paid Amount</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">Rs {{ number_format($invoices->where('status', 'overdue')->sum('total_amount'), 2) }}</h4>
                                <p class="text-muted mb-0">Overdue Amount</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">Rs {{ number_format($invoices->avg('total_amount'), 2) }}</h4>
                                <p class="text-muted mb-0">Average Invoice Value</p>
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
// Auto-submit form on filter change
document.getElementById('customer_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('sales_assistant_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('payment_method').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('start_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('end_date').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
