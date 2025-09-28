@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>Invoices</h2>
    <div>
        <button type="button" class="btn btn-outline-primary me-2" onclick="exportInvoices()">
            <i class="fas fa-download me-1"></i>Export
        </button>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create New Invoice
        </a>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Invoice number, customer...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
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
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $invoices->total() }}</h4>
                        <p class="card-text">Total Invoices</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $invoices->where('status', 'paid')->count() }}</h4>
                        <p class="card-text">Paid Invoices</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $invoices->where('status', 'sent')->count() }}</h4>
                        <p class="card-text">Pending Payment</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $invoices->where('status', 'overdue')->count() }}</h4>
                        <p class="card-text">Overdue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($invoices->count() > 0)
            <!-- Bulk Actions -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleSelectAll()">
                                <i class="fas fa-check-square me-1"></i>Select All
                            </button>
                            <span class="ms-2 text-muted" id="selectedCount">0 selected</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="bulkStatusUpdate('sent')" disabled id="bulkSentBtn">
                                <i class="fas fa-paper-plane me-1"></i>Mark as Sent
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkStatusUpdate('paid')" disabled id="bulkPaidBtn">
                                <i class="fas fa-check me-1"></i>Mark as Paid
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkStatusUpdate('overdue')" disabled id="bulkOverdueBtn">
                                <i class="fas fa-exclamation-triangle me-1"></i>Mark as Overdue
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Sales Assistant</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="{{ $invoice->is_overdue ? 'table-danger' : '' }}">
                                <td>
                                    <input type="checkbox" class="invoice-checkbox" value="{{ $invoice->id }}" onchange="updateSelectedCount()">
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                        @if($invoice->sales_order_id)
                                            <br><small class="text-muted">SO: {{ $invoice->salesOrder?->so_number }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $invoice->customer?->full_name }}</div>
                                        <small class="text-muted">{{ $invoice->customer?->customer_code }}</small>
                                    </div>
                                </td>
                                <td>{{ $invoice->salesAssistant?->full_name }}</td>
                                <td>{{ $invoice->invoice_date?->format('M d, Y') }}</td>
                                <td>
                                    <span class="{{ $invoice->is_overdue ? 'text-danger fw-bold' : '' }}">
                                        {{ $invoice->due_date?->format('M d, Y') }}
                                    </span>
                                    @if($invoice->is_overdue)
                                        <br><small class="text-danger">{{ $invoice->days_overdue }} days overdue</small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $invoice->currency?->symbol ?? 'Rs' }}{{ number_format($invoice->total_amount, 2) }}</strong>
                                        @if($invoice->currency_id && !$invoice->currency?->is_base_currency)
                                            <br><small class="text-muted">Rate: {{ $invoice->exchange_rate }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status_badge }}">{{ ucfirst($invoice->status) }}</span>
                                    @if($invoice->sent_at)
                                        <br><small class="text-muted">Sent: {{ $invoice->sent_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('invoices.show', $invoice) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.export-pdf', $invoice) }}"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Export PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @if($invoice->status === 'draft')
                                            <a href="{{ route('invoices.edit', $invoice) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(in_array($invoice->status, ['draft', 'cancelled']))
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $invoices->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Invoices Found</h5>
                <p class="text-muted">Get started by creating your first invoice.</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Invoice
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.invoice-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });

    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
    const count = checkboxes.length;
    const selectedCount = document.getElementById('selectedCount');
    const bulkButtons = document.querySelectorAll('[id^="bulk"]');

    selectedCount.textContent = count + ' selected';

    // Enable/disable bulk action buttons
    bulkButtons.forEach(button => {
        button.disabled = count === 0;
    });

    // Update select all checkbox state
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const allCheckboxes = document.querySelectorAll('.invoice-checkbox');
    selectAllCheckbox.checked = count === allCheckboxes.length && count > 0;
    selectAllCheckbox.indeterminate = count > 0 && count < allCheckboxes.length;
}

function bulkStatusUpdate(status) {
    const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
    const invoiceIds = Array.from(checkboxes).map(cb => cb.value);

    if (invoiceIds.length === 0) {
        alert('Please select at least one invoice.');
        return;
    }

    if (confirm(`Are you sure you want to update ${invoiceIds.length} invoice(s) status to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("invoices.bulk-status-update") }}';

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        // Add invoice IDs
        invoiceIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invoice_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

function exportInvoices() {
    // This would implement invoice export functionality
    alert('Export functionality will be implemented here');
}
</script>
@endsection