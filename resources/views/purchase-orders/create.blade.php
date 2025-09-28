@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-file-invoice me-2"></i>Create Purchase Order</h4>
        <div class="small text-muted">Create a new purchase order</div>
    </div>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card"><div class="card-body">
<form method="POST" action="{{ route('purchase-orders.store') }}" class="row g-3">
@csrf

<div class="col-md-6"><label class="form-label">Supplier <span class="text-danger">*</span></label><select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required><option value="">Select supplier</option>@foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->company_name }}</option>@endforeach</select>@error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-3"><label class="form-label">Order Date <span class="text-danger">*</span></label><input type="date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" class="form-control @error('order_date') is-invalid @enderror" required>@error('order_date')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-3"><label class="form-label">Expected Delivery</label><input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="form-control @error('expected_delivery_date') is-invalid @enderror">@error('expected_delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

<div class="col-md-4"><label class="form-label">Currency <span class="text-danger">*</span></label><select name="currency_id" class="form-select @error('currency_id') is-invalid @enderror" required><option value="">Select currency</option>@foreach($currencies as $c)<option value="{{ $c->id }}" {{ old('currency_id')==$c->id?'selected':'' }}>{{ $c->code }} - {{ $c->name }}</option>@endforeach</select>@error('currency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label">Exchange Rate <span class="text-danger">*</span></label><input type="number" step="0.0001" name="exchange_rate" value="{{ old('exchange_rate', 1) }}" class="form-control @error('exchange_rate') is-invalid @enderror" required>@error('exchange_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label">Status</label><input class="form-control" value="Draft" disabled></div>

<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea></div>
<div class="col-12"><label class="form-label">Terms & Conditions</label><textarea name="terms_conditions" class="form-control @error('terms_conditions') is-invalid @enderror" rows="3">{{ old('terms_conditions') }}</textarea></div>

</div></div>

<!-- Items Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Items to Order</h5>
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

<div class="col-12"><hr><div class="d-flex justify-content-end gap-2"><a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Create PO</button></div></div>

</form>
</div></div>
@endsection

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
</script>
