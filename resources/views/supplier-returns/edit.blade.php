@extends('layouts.app')

@section('title', 'Edit Supplier Return')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Edit Supplier Return</h1>
                    <p class="text-muted">Return #{{ $supplierReturn->return_number }}</p>
                </div>
                <div>
                    <a href="{{ route('supplier-returns.show', $supplierReturn) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Return
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('supplier-returns.update', $supplierReturn) }}" method="POST" id="returnForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column - Return Details -->
            <div class="col-lg-8">
                <!-- Return Details Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Return Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $supplierReturn->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->company_name }} ({{ $supplier->supplier_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Return Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                                       id="return_date" name="return_date" 
                                       value="{{ old('return_date', $supplierReturn->return_date->format('Y-m-d')) }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select class="form-select @error('currency_id') is-invalid @enderror" 
                                        id="currency_id" name="currency_id" 
                                        data-original-currency-id="{{ $supplierReturn->currency_id }}" required onchange="updateCurrencySymbol()">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" 
                                                {{ old('currency_id', $supplierReturn->currency_id) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="reason" class="form-label">Return Reason <span class="text-danger">*</span></label>
                                <select class="form-select @error('reason') is-invalid @enderror" 
                                        id="reason" name="reason" required>
                                    <option value="">Select Reason</option>
                                    <option value="defective" {{ old('reason', $supplierReturn->reason) == 'defective' ? 'selected' : '' }}>Defective Items</option>
                                    <option value="wrong_item" {{ old('reason', $supplierReturn->reason) == 'wrong_item' ? 'selected' : '' }}>Wrong Item</option>
                                    <option value="overstock" {{ old('reason', $supplierReturn->reason) == 'overstock' ? 'selected' : '' }}>Overstock</option>
                                    <option value="damaged" {{ old('reason', $supplierReturn->reason) == 'damaged' ? 'selected' : '' }}>Damaged in Transit</option>
                                    <option value="quality_issue" {{ old('reason', $supplierReturn->reason) == 'quality_issue' ? 'selected' : '' }}>Quality Issue</option>
                                    <option value="other" {{ old('reason', $supplierReturn->reason) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Additional notes about this return...">{{ old('notes', $supplierReturn->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Return Items</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30%">Item</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="10%">Discount %</th>
                                        <th width="10%">Tax %</th>
                                        <th width="15%">Total</th>
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
            </div>

            <!-- Right Column - Summary -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Return Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotalAmount">{{ number_format($supplierReturn->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span id="discountAmount">{{ number_format($supplierReturn->discount_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span id="taxAmount">{{ number_format($supplierReturn->tax_amount, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="totalAmount">{{ number_format($supplierReturn->total_amount, 2) }}</strong>
                        </div>
                        <div class="text-muted small mt-1">
                            Currency: <span id="currencySymbol">{{ $supplierReturn->currency->code }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i> Update Return
                            </button>
                            <a href="{{ route('supplier-returns.show', $supplierReturn) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let itemRowIndex = 0;
let items = @json($items);
let currencies = @json($currencies);
let existingItems = @json($supplierReturn->transactionItems);

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    updateCurrencySymbol();
    
    // Add existing items
    if (existingItems.length > 0) {
        existingItems.forEach(item => {
            addItemRow({
                item_id: item.item_id,
                quantity: item.quantity,
                unit_price: item.unit_price,
                discount_percentage: item.discount_percentage,
                tax_percentage: item.tax_percentage,
                total_price: item.total_price,
                discount_amount: item.discount_amount,
                tax_amount: item.tax_amount
            });
        });
    } else {
        addItemRow();
    }
});

function addItemRow(itemData = null) {
    const tbody = document.getElementById('itemsTableBody');
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td>
            <select class="form-select item-select" name="items[${itemRowIndex}][item_id]" required onchange="updateItemDetails(${itemRowIndex})">
                <option value="">Select Item</option>
                ${items.map(item => `
                    <option value="${item.id}" ${itemData && itemData.item_id == item.id ? 'selected' : ''}>
                        ${item.name} (${item.item_code})
                    </option>
                `).join('')}
            </select>
            <input type="hidden" name="items[${itemRowIndex}][item_code]" class="item-code">
            <input type="hidden" name="items[${itemRowIndex}][item_name]" class="item-name">
        </td>
        <td>
            <input type="number" class="form-control quantity" name="items[${itemRowIndex}][quantity]" 
                   value="${itemData ? itemData.quantity : ''}" step="0.001" min="0.001" required 
                   onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control unit-price" name="items[${itemRowIndex}][unit_price]" 
                   value="${itemData ? itemData.unit_price : ''}" step="0.01" min="0" required 
                   onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control discount-percentage" name="items[${itemRowIndex}][discount_percentage]" 
                   value="${itemData ? (itemData.discount_percentage || 0) : 0}" step="0.01" min="0" max="100" 
                   onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control tax-percentage" name="items[${itemRowIndex}][tax_percentage]" 
                   value="${itemData ? (itemData.tax_percentage || 0) : 0}" step="0.01" min="0" max="100" 
                   onchange="calculateRowTotal(${itemRowIndex})">
        </td>
        <td>
            <input type="number" class="form-control total-price" name="items[${itemRowIndex}][total_price]" 
                   value="${itemData ? itemData.total_price : 0}" step="0.01" readonly>
            <input type="hidden" name="items[${itemRowIndex}][discount_amount]" class="discount-amount">
            <input type="hidden" name="items[${itemRowIndex}][tax_amount]" class="tax-amount">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItemRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    itemRowIndex++;
    
    if (itemData && itemData.item_id) {
        updateItemDetails(itemRowIndex - 1);
        calculateRowTotal(itemRowIndex - 1);
    }
}

function removeItemRow(button) {
    button.closest('tr').remove();
    calculateTotals();
}

function updateItemDetails(rowIndex) {
    const row = document.querySelectorAll('#itemsTableBody tr')[rowIndex];
    const itemSelect = row.querySelector('.item-select');
    const selectedItem = items.find(item => item.id == itemSelect.value);
    
    if (selectedItem) {
        row.querySelector('.item-code').value = selectedItem.item_code;
        row.querySelector('.item-name').value = selectedItem.name;
        if (!row.querySelector('.unit-price').value) {
            row.querySelector('.unit-price').value = selectedItem.selling_price || 0;
        }
        calculateRowTotal(rowIndex);
    }
}

function calculateRowTotal(rowIndex) {
    const row = document.querySelectorAll('#itemsTableBody tr')[rowIndex];
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discountPercentage = parseFloat(row.querySelector('.discount-percentage').value) || 0;
    const taxPercentage = parseFloat(row.querySelector('.tax-percentage').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const discountAmount = (subtotal * discountPercentage) / 100;
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = (taxableAmount * taxPercentage) / 100;
    const total = subtotal - discountAmount + taxAmount;
    
    row.querySelector('.total-price').value = total.toFixed(2);
    row.querySelector('.discount-amount').value = discountAmount.toFixed(2);
    row.querySelector('.tax-amount').value = taxAmount.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    const rows = document.querySelectorAll('#itemsTableBody tr');
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    rows.forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discountPercentage = parseFloat(row.querySelector('.discount-percentage').value) || 0;
        const taxPercentage = parseFloat(row.querySelector('.tax-percentage').value) || 0;
        
        const rowSubtotal = quantity * unitPrice;
        const rowDiscount = (rowSubtotal * discountPercentage) / 100;
        const rowTaxableAmount = rowSubtotal - rowDiscount;
        const rowTax = (rowTaxableAmount * taxPercentage) / 100;
        
        subtotal += rowSubtotal;
        totalDiscount += rowDiscount;
        totalTax += rowTax;
    });
    
    const total = subtotal - totalDiscount + totalTax;
    
    document.getElementById('subtotalAmount').textContent = subtotal.toFixed(2);
    document.getElementById('discountAmount').textContent = totalDiscount.toFixed(2);
    document.getElementById('taxAmount').textContent = totalTax.toFixed(2);
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

function updateCurrencySymbol() {
    const currencySelect = document.getElementById('currency_id');
    const selectedOption = currencySelect.options[currencySelect.selectedIndex];
    const currencyCode = selectedOption.text.split(' - ')[0];
    document.getElementById('currencySymbol').textContent = currencyCode;
}

// Currency conversion functionality
function convertItemPrices() {
    const currencySelect = document.getElementById('currency_id');
    const newCurrencyId = currencySelect.value;
    const originalCurrencyId = currencySelect.dataset.originalCurrencyId;
    
    if (newCurrencyId === originalCurrencyId) {
        return; // No conversion needed
    }
    
    const originalCurrency = currencies.find(c => c.id == originalCurrencyId);
    const newCurrency = currencies.find(c => c.id == newCurrencyId);
    
    if (!originalCurrency || !newCurrency) {
        return;
    }
    
    // Convert all unit prices
    const unitPriceInputs = document.querySelectorAll('.unit-price');
    unitPriceInputs.forEach(input => {
        const originalPrice = parseFloat(input.value) || 0;
        if (originalPrice > 0) {
            const convertedPrice = (originalPrice * originalCurrency.exchange_rate) / newCurrency.exchange_rate;
            input.value = convertedPrice.toFixed(2);
            input.dispatchEvent(new Event('change')); // Trigger calculation
        }
    });
    
    // Store the original currency ID for future reference
    currencySelect.dataset.originalCurrencyId = newCurrencyId;
}

// Add event listener for currency change
document.getElementById('currency_id').addEventListener('change', function() {
    updateCurrencySymbol();
    convertItemPrices();
});

// Handle form submission to ensure converted values are submitted
document.getElementById('returnForm').addEventListener('submit', function(e) {
    // Ensure all converted values are properly set before submission
    const rows = document.querySelectorAll('#itemsTableBody tr');
    rows.forEach((row, index) => {
        const unitPriceInput = row.querySelector('.unit-price');
        const totalPriceInput = row.querySelector('.total-price');
        
        // Make sure the values are properly formatted
        if (unitPriceInput.value) {
            unitPriceInput.value = parseFloat(unitPriceInput.value).toFixed(2);
        }
        if (totalPriceInput.value) {
            totalPriceInput.value = parseFloat(totalPriceInput.value).toFixed(2);
        }
    });
});

// Form validation
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const itemRows = document.querySelectorAll('#itemsTableBody tr');
    
    if (itemRows.length === 0) {
        e.preventDefault();
        alert('Please add at least one item to the return.');
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
