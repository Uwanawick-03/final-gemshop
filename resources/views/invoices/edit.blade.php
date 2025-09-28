@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Invoice #{{ $invoice->invoice_number }}</h2>
    <div>
        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-eye me-2"></i>View Invoice
        </a>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Invoices
        </a>
    </div>
</div>

<form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->full_name }} ({{ $customer->customer_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sales_assistant_id" class="form-label">Sales Assistant <span class="text-danger">*</span></label>
                            <select class="form-select @error('sales_assistant_id') is-invalid @enderror" 
                                    id="sales_assistant_id" name="sales_assistant_id" required>
                                <option value="">Select Sales Assistant</option>
                                @foreach($salesAssistants as $assistant)
                                    <option value="{{ $assistant->id }}" 
                                            {{ old('sales_assistant_id', $invoice->sales_assistant_id) == $assistant->id ? 'selected' : '' }}>
                                        {{ $assistant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_assistant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                   id="invoice_date" name="invoice_date" 
                                   value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice Items</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                        <i class="fas fa-plus me-1"></i>Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead>
                                <tr>
                                    <th width="30%">Item</th>
                                    <th width="10%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th width="10%">Discount %</th>
                                    <th width="10%">Discount Amount</th>
                                    <th width="10%">Tax %</th>
                                    <th width="10%">Tax Amount</th>
                                    <th width="10%">Total</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                              placeholder="Additional notes or terms...">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Invoice Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rs 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Discount:</span>
                        <span id="totalDiscount">Rs 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Tax:</span>
                        <span id="totalTax">Rs 0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Amount:</span>
                        <span id="totalAmount">Rs 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Invoice
                        </button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
let itemRowIndex = 0;

// Load existing items when page loads
document.addEventListener('DOMContentLoaded', function() {
    @if($invoice->transactionItems->count() > 0)
        @foreach($invoice->transactionItems as $index => $item)
            addItemRow();
            // Use setTimeout to ensure the row is created before accessing it
            setTimeout(function() {
                const row = document.getElementById('itemRow' + {{ $index }});
                if (row) {
                    row.querySelector('.item-select').value = '{{ $item->item_id }}';
                    row.querySelector('.quantity').value = '{{ $item->quantity }}';
                    row.querySelector('.unit-price').value = '{{ $item->unit_price }}';
                    row.querySelector('.discount-percent').value = '{{ $item->discount_percentage }}';
                    row.querySelector('.tax-percent').value = '{{ $item->tax_percentage }}';
                    row.querySelector('.discount-amount').value = '{{ $item->discount_amount }}';
                    row.querySelector('.tax-amount').value = '{{ $item->tax_amount }}';
                    row.querySelector('.row-total').value = '{{ number_format($item->total_price, 2) }}';
                    calculateRowTotal({{ $index }});
                }
            }, 10); // Small delay to ensure DOM is ready
        @endforeach
    @else
        addItemRow();
    @endif
});

function addItemRow() {
    const tbody = document.getElementById('itemsTableBody');
    const row = document.createElement('tr');
    row.id = 'itemRow' + itemRowIndex;
    
    row.innerHTML = `
        <td>
            <select class="form-select item-select" name="items[${itemRowIndex}][item_id]" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-price="{{ $item->selling_price }}">
                        {{ $item->name }} - {{ displayAmount($item->selling_price) }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity" name="items[${itemRowIndex}][quantity]" 
                   min="1" value="1" required onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control unit-price" name="items[${itemRowIndex}][unit_price]" 
                   step="0.01" min="0" required onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control discount-percent" name="items[${itemRowIndex}][discount_percentage]" 
                   step="0.01" min="0" max="100" value="0" onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control discount-amount" name="items[${itemRowIndex}][discount_amount]" 
                   step="0.01" min="0" value="0" readonly>
        </td>
        <td>
            <input type="number" class="form-control tax-percent" name="items[${itemRowIndex}][tax_percentage]" 
                   step="0.01" min="0" max="100" value="0" onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control tax-amount" name="items[${itemRowIndex}][tax_amount]" 
                   step="0.01" min="0" value="0" readonly>
        </td>
        <td>
            <input type="number" class="form-control row-total" readonly value="0">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(${itemRowIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Add event listener for item selection
    const itemSelect = row.querySelector('.item-select');
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const unitPriceInput = row.querySelector('.unit-price');
            unitPriceInput.value = price;
            calculateRowTotal(itemRowIndex);
        }
    });
    
    itemRowIndex++;
}

function removeItemRow(index) {
    const row = document.getElementById('itemRow' + index);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function calculateRowTotal(index) {
    const row = document.getElementById('itemRow' + index);
    if (!row) return;
    
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discountPercent = parseFloat(row.querySelector('.discount-percent').value) || 0;
    const taxPercent = parseFloat(row.querySelector('.tax-percent').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const discountAmount = (subtotal * discountPercent) / 100;
    const taxAmount = ((subtotal - discountAmount) * taxPercent) / 100;
    const total = subtotal - discountAmount + taxAmount;
    
    row.querySelector('.discount-amount').value = discountAmount.toFixed(2);
    row.querySelector('.tax-amount').value = taxAmount.toFixed(2);
    row.querySelector('.row-total').value = total.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    const rows = document.querySelectorAll('#itemsTableBody tr');
    rows.forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discountAmount = parseFloat(row.querySelector('.discount-amount').value) || 0;
        const taxAmount = parseFloat(row.querySelector('.tax-amount').value) || 0;
        
        subtotal += quantity * unitPrice;
        totalDiscount += discountAmount;
        totalTax += taxAmount;
    });
    
    const totalAmount = subtotal - totalDiscount + totalTax;
    
    document.getElementById('subtotal').textContent = 'Rs ' + subtotal.toFixed(2);
    document.getElementById('totalDiscount').textContent = 'Rs ' + totalDiscount.toFixed(2);
    document.getElementById('totalTax').textContent = 'Rs ' + totalTax.toFixed(2);
    document.getElementById('totalAmount').textContent = 'Rs ' + totalAmount.toFixed(2);
}

// Form validation
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    const itemRows = document.querySelectorAll('#itemsTableBody tr');
    if (itemRows.length === 0) {
        e.preventDefault();
        alert('Please add at least one item to the invoice.');
        return;
    }
    
    let hasValidItems = false;
    itemRows.forEach(row => {
        const itemSelect = row.querySelector('.item-select');
        const quantity = row.querySelector('.quantity');
        const unitPrice = row.querySelector('.unit-price');
        
        if (itemSelect.value && quantity.value && unitPrice.value) {
            hasValidItems = true;
        }
    });
    
    if (!hasValidItems) {
        e.preventDefault();
        alert('Please ensure all items have valid item, quantity, and unit price.');
        return;
    }
});
</script>
@endsection
