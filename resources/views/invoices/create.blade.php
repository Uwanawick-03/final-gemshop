@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice me-2"></i>Create New Invoice</h2>
    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Invoices
    </a>
</div>

<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <!-- Invoice Header -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required onchange="loadCustomerDetails()">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                    <option value="{{ $assistant->id }}" {{ old('sales_assistant_id') == $assistant->id ? 'selected' : '' }}>
                                        {{ $assistant->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_assistant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                   id="invoice_date" name="invoice_date" 
                                   value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" 
                                   value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency_id') is-invalid @enderror" 
                                    id="currency_id" name="currency_id" required onchange="updateCurrencySymbol()">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" 
                                            {{ ($currency->is_base_currency || old('currency_id') == $currency->id) ? 'selected' : '' }}
                                            data-symbol="{{ $currency->symbol }}"
                                            data-rate="{{ $currency->exchange_rate }}">
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sales_order_id" class="form-label">Sales Order (Optional)</label>
                            <select class="form-select" id="sales_order_id" name="sales_order_id" onchange="loadSalesOrderItems()">
                                <option value="">Select Sales Order</option>
                                @foreach($salesOrders as $so)
                                    <option value="{{ $so->id }}" {{ old('sales_order_id') == $so->id ? 'selected' : '' }}>
                                        {{ $so->so_number }} - {{ $so->customer->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="payment_terms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="payment_terms" name="payment_terms">
                                <option value="">Select Payment Terms</option>
                                <option value="Net 15" {{ old('payment_terms') == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                                <option value="Net 30" {{ old('payment_terms') == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                                <option value="Net 45" {{ old('payment_terms') == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                                <option value="Net 60" {{ old('payment_terms') == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                                <option value="Due on Receipt" {{ old('payment_terms') == 'Due on Receipt' ? 'selected' : '' }}>Due on Receipt</option>
                            </select>
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

            <!-- Notes and Terms -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes or comments...">{{ old('notes') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                            <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="3" 
                                      placeholder="Payment terms and conditions...">{{ old('terms_conditions') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Customer Information</h6>
                </div>
                <div class="card-body" id="customerInfo">
                    <div class="text-center text-muted">
                        <i class="fas fa-user fa-2x mb-2"></i>
                        <p>Select a customer to view details</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Invoice Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal"><span id="currencySymbol">Rs</span> 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Discount:</span>
                        <span id="totalDiscount"><span id="currencySymbol2">Rs</span> 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Tax:</span>
                        <span id="totalTax"><span id="currencySymbol3">Rs</span> 0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Amount:</span>
                        <span id="totalAmount"><span id="currencySymbol4">Rs</span> 0.00</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Invoice
                        </button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
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
let currentCurrencySymbol = 'Rs';

// Add initial item row
document.addEventListener('DOMContentLoaded', function() {
    addItemRow();
    updateCurrencySymbol();
});

function addItemRow(itemData = null) {
    const tbody = document.getElementById('itemsTableBody');
    const row = document.createElement('tr');
    row.id = 'itemRow' + itemRowIndex;
    
    row.innerHTML = `
        <td>
            <select class="form-select item-select" name="items[${itemRowIndex}][item_id]" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" 
                            data-price="{{ $item->selling_price }}"
                            data-stock="{{ $item->current_stock }}"
                            data-tax="{{ $item->tax_rate }}"
                            ${itemData && itemData.item_id == {{ $item->id }} ? 'selected' : ''}>
                        {{ $item->name }} - ${currentCurrencySymbol}{{ number_format($item->selling_price, 2) }}
                        @if($item->current_stock <= 0)
                            <span class="text-danger">(Out of Stock)</span>
                        @elseif($item->current_stock <= 10)
                            <span class="text-warning">(Low Stock: {{ $item->current_stock }})</span>
                        @endif
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity" name="items[${itemRowIndex}][quantity]" 
                   min="1" value="${itemData ? itemData.quantity : 1}" required onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control unit-price" name="items[${itemRowIndex}][unit_price]" 
                   step="0.01" min="0" value="${itemData ? itemData.unit_price : ''}" required onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control discount-percent" name="items[${itemRowIndex}][discount_percentage]" 
                   step="0.01" min="0" max="100" value="${itemData ? itemData.discount_percentage : 0}" onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control discount-amount" name="items[${itemRowIndex}][discount_amount]" 
                   step="0.01" min="0" value="${itemData ? itemData.discount_amount : 0}" readonly>
        </td>
        <td>
            <input type="number" class="form-control tax-percent" name="items[${itemRowIndex}][tax_percentage]" 
                   step="0.01" min="0" max="100" value="${itemData ? itemData.tax_percentage : 0}" onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control tax-amount" name="items[${itemRowIndex}][tax_amount]" 
                   step="0.01" min="0" value="${itemData ? itemData.tax_amount : 0}" readonly>
        </td>
        <td>
            <input type="number" class="form-control row-total" readonly value="${itemData ? itemData.total_price : 0}">
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
            const taxRate = selectedOption.getAttribute('data-tax');
            const stock = selectedOption.getAttribute('data-stock');
            const unitPriceInput = row.querySelector('.unit-price');
            const taxPercentInput = row.querySelector('.tax-percent');
            
            unitPriceInput.value = price;
            taxPercentInput.value = taxRate;
            
            // Check stock availability
            if (stock <= 0) {
                alert('Warning: This item is out of stock!');
            } else if (stock <= 10) {
                alert(`Warning: Low stock available (${stock} items)`);
            }
            
            calculateRowTotal(itemRowIndex);
        }
    });
    
    itemRowIndex++;
    
    // Calculate totals if this is not the initial load
    if (itemData) {
        calculateRowTotal(itemRowIndex - 1);
    }
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
    
    document.getElementById('subtotal').innerHTML = currentCurrencySymbol + ' ' + subtotal.toFixed(2);
    document.getElementById('totalDiscount').innerHTML = currentCurrencySymbol + ' ' + totalDiscount.toFixed(2);
    document.getElementById('totalTax').innerHTML = currentCurrencySymbol + ' ' + totalTax.toFixed(2);
    document.getElementById('totalAmount').innerHTML = currentCurrencySymbol + ' ' + totalAmount.toFixed(2);
}

function updateCurrencySymbol() {
    const currencySelect = document.getElementById('currency_id');
    const selectedOption = currencySelect.options[currencySelect.selectedIndex];
    currentCurrencySymbol = selectedOption.getAttribute('data-symbol');
    
    // Update all currency symbols in the summary
    document.querySelectorAll('[id^="currencySymbol"]').forEach(element => {
        element.textContent = currentCurrencySymbol;
    });
    
    calculateTotals();
}

function loadCustomerDetails() {
    const customerId = document.getElementById('customer_id').value;
    const customerInfoDiv = document.getElementById('customerInfo');
    
    if (!customerId) {
        customerInfoDiv.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-user fa-2x mb-2"></i>
                <p>Select a customer to view details</p>
            </div>
        `;
        return;
    }
    
    // Here you would typically make an AJAX call to get customer details
    // For now, we'll just show a loading message
    customerInfoDiv.innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
            <p>Loading customer details...</p>
        </div>
    `;
}

function loadSalesOrderItems() {
    const salesOrderId = document.getElementById('sales_order_id').value;
    
    if (!salesOrderId) {
        return;
    }
    
    // Show loading indicator
    const itemsTableBody = document.getElementById('itemsTableBody');
    itemsTableBody.innerHTML = '<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading items...</td></tr>';
    
    fetch(`{{ route('invoices.get-by-sales-order') }}?sales_order_id=${salesOrderId}`)
        .then(response => response.json())
        .then(data => {
            // Clear existing items
            itemsTableBody.innerHTML = '';
            itemRowIndex = 0;
            
            // Set customer if not already set
            if (data.customer && !document.getElementById('customer_id').value) {
                document.getElementById('customer_id').value = data.customer.id;
                loadCustomerDetails();
            }
            
            // Add items from sales order
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    addItemRow({
                        item_id: item.item_id,
                        quantity: item.quantity,
                        unit_price: item.unit_price,
                        discount_percentage: item.discount_percentage,
                        tax_percentage: item.tax_percentage,
                        discount_amount: item.discount_amount,
                        tax_amount: item.tax_amount,
                        total_price: item.total_price
                    });
                });
            } else {
                addItemRow(); // Add at least one empty row
            }
        })
        .catch(error => {
            console.error('Error loading sales order items:', error);
            itemsTableBody.innerHTML = '';
            itemRowIndex = 0;
            addItemRow();
            alert('Error loading sales order items. Please try again.');
        });
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