@extends('layouts.app')

@section('title', 'Create Sales Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i>Create Sales Order</h4>
        <div class="small text-muted">Create a new sales order</div>
    </div>
    <a href="{{ route('sales-orders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('sales-orders.store') }}" id="salesOrderForm">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select customer</option>
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

                <div class="col-md-6">
                    <label class="form-label">Sales Assistant <span class="text-danger">*</span></label>
                    <select name="sales_assistant_id" class="form-select @error('sales_assistant_id') is-invalid @enderror" required>
                        <option value="">Select sales assistant</option>
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

                <div class="col-md-3">
                    <label class="form-label">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" 
                           class="form-control @error('order_date') is-invalid @enderror" required>
                    @error('order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Delivery Date</label>
                    <input type="date" name="delivery_date" value="{{ old('delivery_date') }}" 
                           class="form-control @error('delivery_date') is-invalid @enderror">
                    @error('delivery_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Order Items</h5>
            <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                <i class="fas fa-plus me-1"></i>Add Item
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th width="25%">Item</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Unit Price</th>
                            <th width="10%">Discount %</th>
                            <th width="10%">Tax %</th>
                            <th width="15%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <!-- Items will be added here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Subtotal:</th>
                            <th id="subtotal">$0.00</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">Total Discount:</th>
                            <th id="totalDiscount">$0.00</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">Total Tax:</th>
                            <th id="totalTax">$0.00</th>
                            <th></th>
                        </tr>
                        <tr class="table-primary">
                            <th colspan="5" class="text-end">Grand Total:</th>
                            <th id="grandTotal">$0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Create Sales Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden input for total amount -->
    <input type="hidden" name="total_amount" id="totalAmount" value="0">
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCounter = 0;
    const items = @json($items);
    
    // Add first item row
    addItemRow();
    
    // Add item button click handler
    document.getElementById('addItemBtn').addEventListener('click', addItemRow);
    
    function addItemRow() {
        itemCounter++;
        const tbody = document.getElementById('itemsTableBody');
        const row = document.createElement('tr');
        row.id = `itemRow${itemCounter}`;
        
        row.innerHTML = `
            <td>
                <select name="items[${itemCounter}][item_id]" class="form-select item-select" required>
                    <option value="">Select item</option>
                    ${items.map(item => `<option value="${item.id}" data-price="${item.selling_price || 0}">${item.item_code} - ${item.name}</option>`).join('')}
                </select>
                <input type="hidden" name="items[${itemCounter}][item_code]" class="item-code">
                <input type="hidden" name="items[${itemCounter}][item_name]" class="item-name">
            </td>
            <td>
                <input type="number" name="items[${itemCounter}][quantity]" class="form-control quantity" 
                       min="1" step="1" value="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemCounter}][unit_price]" class="form-control unit-price" 
                       min="0" step="0.01" value="0" required>
            </td>
            <td>
                <input type="number" name="items[${itemCounter}][discount_percentage]" class="form-control discount-percentage" 
                       min="0" max="100" step="0.01" value="0">
            </td>
            <td>
                <input type="number" name="items[${itemCounter}][tax_percentage]" class="form-control tax-percentage" 
                       min="0" max="100" step="0.01" value="0">
            </td>
            <td>
                <input type="text" class="form-control total-price" readonly value="$0.00">
                <input type="hidden" name="items[${itemCounter}][total_price]" class="total-price-hidden">
                <input type="hidden" name="items[${itemCounter}][discount_amount]" class="discount-amount">
                <input type="hidden" name="items[${itemCounter}][tax_amount]" class="tax-amount">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItemRow(${itemCounter})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
        
        // Add event listeners to the new row
        const itemSelect = row.querySelector('.item-select');
        const quantity = row.querySelector('.quantity');
        const unitPrice = row.querySelector('.unit-price');
        const discountPercentage = row.querySelector('.discount-percentage');
        const taxPercentage = row.querySelector('.tax-percentage');
        
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            unitPrice.value = price;
            row.querySelector('.item-code').value = selectedOption.text.split(' - ')[0];
            row.querySelector('.item-name').value = selectedOption.text.split(' - ')[1] || '';
            calculateRowTotal(row);
        });
        
        [quantity, unitPrice, discountPercentage, taxPercentage].forEach(input => {
            input.addEventListener('input', function() {
                calculateRowTotal(row);
            });
        });
    }
    
    window.removeItemRow = function(rowId) {
        const row = document.getElementById(`itemRow${rowId}`);
        if (row) {
            row.remove();
            calculateTotals();
        }
    };
    
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discountPercentage = parseFloat(row.querySelector('.discount-percentage').value) || 0;
        const taxPercentage = parseFloat(row.querySelector('.tax-percentage').value) || 0;
        
        const subtotal = quantity * unitPrice;
        const discountAmount = subtotal * (discountPercentage / 100);
        const afterDiscount = subtotal - discountAmount;
        const taxAmount = afterDiscount * (taxPercentage / 100);
        const total = afterDiscount + taxAmount;
        
        row.querySelector('.total-price').value = `$${total.toFixed(2)}`;
        row.querySelector('.total-price-hidden').value = total;
        row.querySelector('.discount-amount').value = discountAmount;
        row.querySelector('.tax-amount').value = taxAmount;
        
        calculateTotals();
    }
    
    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        
        document.querySelectorAll('#itemsTableBody tr').forEach(row => {
            const totalPrice = parseFloat(row.querySelector('.total-price-hidden').value) || 0;
            const discountAmount = parseFloat(row.querySelector('.discount-amount').value) || 0;
            const taxAmount = parseFloat(row.querySelector('.tax-amount').value) || 0;
            
            subtotal += totalPrice - taxAmount + discountAmount;
            totalDiscount += discountAmount;
            totalTax += taxAmount;
        });
        
        const grandTotal = subtotal + totalTax - totalDiscount;
        
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('totalDiscount').textContent = `$${totalDiscount.toFixed(2)}`;
        document.getElementById('totalTax').textContent = `$${totalTax.toFixed(2)}`;
        document.getElementById('grandTotal').textContent = `$${grandTotal.toFixed(2)}`;
        document.getElementById('totalAmount').value = grandTotal;
    }
    
    // Form validation
    document.getElementById('salesOrderForm').addEventListener('submit', function(e) {
        const itemRows = document.querySelectorAll('#itemsTableBody tr');
        let hasValidItem = false;
        
        itemRows.forEach(row => {
            const itemSelect = row.querySelector('.item-select');
            const quantity = row.querySelector('.quantity');
            const unitPrice = row.querySelector('.unit-price');
            
            if (itemSelect.value && quantity.value > 0 && unitPrice.value > 0) {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            e.preventDefault();
            alert('Please add at least one item with quantity and price.');
            return false;
        }
    });
});
</script>
@endsection