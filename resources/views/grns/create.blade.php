@extends('layouts.app')

@section('title', 'Create GRN')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i>Create New GRN</h2>
    <a href="{{ route('grns.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to GRNs
    </a>
</div>

<form id="grnForm" action="{{ route('grns.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">GRN Information</h5>
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
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="purchase_order_id" class="form-label">Purchase Order (Optional)</label>
                            <select class="form-select" id="purchase_order_id" name="purchase_order_id" onchange="loadPurchaseOrderItems()">
                                <option value="">Select Purchase Order</option>
                                @foreach($purchaseOrders as $po)
                                    <option value="{{ $po->id }}" 
                                            {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>
                                        {{ $po->po_number }} - {{ $po->supplier->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="grn_date" class="form-label">GRN Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('grn_date') is-invalid @enderror" 
                                   id="grn_date" name="grn_date" 
                                   value="{{ old('grn_date', date('Y-m-d')) }}" required>
                            @error('grn_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="received_date" class="form-label">Received Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('received_date') is-invalid @enderror" 
                                   id="received_date" name="received_date" 
                                   value="{{ old('received_date', date('Y-m-d')) }}" required>
                            @error('received_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency_id') is-invalid @enderror" 
                                    id="currency_id" name="currency_id" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" 
                                            {{ old('currency_id', $currency->is_base_currency ? $currency->id : '') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="exchange_rate" class="form-label">Exchange Rate</label>
                            <input type="number" step="0.0001" class="form-control @error('exchange_rate') is-invalid @enderror" 
                                   id="exchange_rate" name="exchange_rate" 
                                   value="{{ old('exchange_rate', 1.0000) }}">
                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="delivery_person" class="form-label">Delivery Person</label>
                            <input type="text" class="form-control @error('delivery_person') is-invalid @enderror" 
                                   id="delivery_person" name="delivery_person" 
                                   value="{{ old('delivery_person') }}">
                            @error('delivery_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                   id="vehicle_number" name="vehicle_number" 
                                   value="{{ old('vehicle_number') }}">
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delivery_notes" class="form-label">Delivery Notes</label>
                        <textarea class="form-control @error('delivery_notes') is-invalid @enderror" 
                                  id="delivery_notes" name="delivery_notes" rows="2">{{ old('delivery_notes') }}</textarea>
                        @error('delivery_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Items Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Items Received</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItemRow()">
                        <i class="fas fa-plus me-1"></i>Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
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
                                <!-- Items will be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-end">
                                <div class="text-end">
                                    <div class="mb-2">
                                        <strong>Subtotal: <span id="subtotal">$0.00</span></strong>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Discount: <span id="discount">$0.00</span></strong>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Tax: <span id="tax">$0.00</span></strong>
                                    </div>
                                    <hr>
                                    <div class="h5">
                                        <strong>Total: <span id="total">$0.00</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Help Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">GRN Help</h6>
                </div>
                <div class="card-body">
                    <h6>GRN Process:</h6>
                    <ol class="small">
                        <li>Select supplier and purchase order (if applicable)</li>
                        <li>Enter GRN and received dates</li>
                        <li>Add received items with quantities and prices</li>
                        <li>Review totals and submit</li>
                    </ol>
                    
                    <h6 class="mt-3">Status Flow:</h6>
                    <ul class="list-unstyled small">
                        <li>• <strong>Draft:</strong> Being prepared</li>
                        <li>• <strong>Received:</strong> Goods received</li>
                        <li>• <strong>Verified:</strong> Quality checked</li>
                        <li>• <strong>Completed:</strong> Process finished</li>
                    </ul>
                    
                    <h6 class="mt-3">Stock Update:</h6>
                    <p class="small text-muted">
                        Item stock will be automatically updated when GRN is created.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Buttons -->
    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('grns.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Create GRN
        </button>
    </div>
</form>

<script>
let itemRowCount = 0;

function addItemRow() {
    const tbody = document.getElementById('itemsTableBody');
    const row = document.createElement('tr');
    row.id = 'itemRow' + itemRowCount;
    
    row.innerHTML = `
        <td>
            <select class="form-select item-select" name="items[${itemRowCount}][item_id]" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-price="{{ $item->cost_price }}">
                        {{ $item->item_code }} - {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity" name="items[${itemRowCount}][quantity]" 
                   min="1" value="1" required onchange="calculateRowTotal(${itemRowCount})">
        </td>
        <td>
            <input type="number" step="0.01" class="form-control unit-price" name="items[${itemRowCount}][unit_price]" 
                   min="0" value="0" required onchange="calculateRowTotal(${itemRowCount})">
        </td>
        <td>
            <input type="number" step="0.01" class="form-control discount-percent" name="items[${itemRowCount}][discount_percentage]" 
                   min="0" max="100" value="0" onchange="calculateRowTotal(${itemRowCount})">
        </td>
        <td>
            <input type="number" step="0.01" class="form-control tax-percent" name="items[${itemRowCount}][tax_percentage]" 
                   min="0" max="100" value="0" onchange="calculateRowTotal(${itemRowCount})">
        </td>
        <td>
            <input type="text" class="form-control row-total" readonly value="0.00">
            <input type="hidden" class="discount-amount" name="items[${itemRowCount}][discount_amount]" value="0">
            <input type="hidden" class="tax-amount" name="items[${itemRowCount}][tax_amount]" value="0">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItemRow(${itemRowCount})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    itemRowCount++;
    
    // Add event listener for item selection
    const itemSelect = row.querySelector('.item-select');
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        if (price) {
            row.querySelector('.unit-price').value = price;
            calculateRowTotal(itemRowCount - 1);
        }
    });
}

function removeItemRow(rowId) {
    const row = document.getElementById('itemRow' + rowId);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function calculateRowTotal(rowIndex) {
    const row = document.getElementById('itemRow' + rowIndex);
    if (!row) return;
    
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discountPercent = parseFloat(row.querySelector('.discount-percent').value) || 0;
    const taxPercent = parseFloat(row.querySelector('.tax-percent').value) || 0;
    
    const subtotal = quantity * unitPrice;
    const discountAmount = (subtotal * discountPercent) / 100;
    const afterDiscount = subtotal - discountAmount;
    const taxAmount = (afterDiscount * taxPercent) / 100;
    const total = afterDiscount + taxAmount;
    
    row.querySelector('.row-total').value = total.toFixed(2);
    row.querySelector('.discount-amount').value = discountAmount.toFixed(2);
    row.querySelector('.tax-amount').value = taxAmount.toFixed(2);
    
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    const rows = document.querySelectorAll('#itemsTableBody tr');
    rows.forEach(row => {
        const rowTotal = parseFloat(row.querySelector('.row-total').value) || 0;
        const discountAmount = parseFloat(row.querySelector('.discount-amount').value) || 0;
        const taxAmount = parseFloat(row.querySelector('.tax-amount').value) || 0;
        
        subtotal += rowTotal;
        totalDiscount += discountAmount;
        totalTax += taxAmount;
    });
    
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('discount').textContent = '$' + totalDiscount.toFixed(2);
    document.getElementById('tax').textContent = '$' + totalTax.toFixed(2);
    document.getElementById('total').textContent = '$' + subtotal.toFixed(2);
}

// Add first row on page load
document.addEventListener('DOMContentLoaded', function() {
    addItemRow();
});

function loadPurchaseOrderItems() {
    const purchaseOrderId = document.getElementById('purchase_order_id').value;
    
    if (!purchaseOrderId) {
        return;
    }
    
    // Show loading indicator
    const itemsTableBody = document.getElementById('itemsTableBody');
    itemsTableBody.innerHTML = '<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading items...</td></tr>';
    
    fetch(`{{ route('grns.get-by-po') }}?purchase_order_id=${purchaseOrderId}`)
        .then(response => response.json())
        .then(data => {
            // Clear existing items
            itemsTableBody.innerHTML = '';
            itemRowCount = 0;
            
            // Set supplier
            if (data.supplier) {
                document.getElementById('supplier_id').value = data.supplier.id;
            }
            
            // Add items from purchase order
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    addItemRow({
                        item_id: item.item_id,
                        quantity: item.quantity,
                        unit_price: item.unit_price,
                        discount_percentage: item.discount_percentage,
                        tax_percentage: item.tax_percentage
                    });
                });
            } else {
                addItemRow(); // Add at least one empty row
            }
        })
        .catch(error => {
            console.error('Error loading purchase order items:', error);
            itemsTableBody.innerHTML = '';
            itemRowCount = 0;
            addItemRow();
            alert('Error loading purchase order items. Please try again.');
        });
}
</script>
@endsection












