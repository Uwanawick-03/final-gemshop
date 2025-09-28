@extends('layouts.app')

@section('title', 'Edit Customer Return')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Customer Return</h2>
    <div>
        <a href="{{ route('customer-returns.show', $customerReturn) }}" class="btn btn-outline-info me-2">
            <i class="fas fa-eye me-1"></i>View Return
        </a>
        <a href="{{ route('customer-returns.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Returns
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Return Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customer-returns.update', $customerReturn) }}" method="POST" id="returnForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id', $customerReturn->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->full_name }} ({{ $customer->customer_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="return_date" class="form-label">Return Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                                   id="return_date" name="return_date" 
                                   value="{{ old('return_date', $customerReturn->return_date->format('Y-m-d')) }}" required>
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('currency_id') is-invalid @enderror" 
                                    id="currency_id" name="currency_id" 
                                    data-original-currency-id="{{ $customerReturn->currency_id }}" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" 
                                            {{ old('currency_id', $customerReturn->currency_id) == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('reason') is-invalid @enderror" 
                                   id="reason" name="reason" 
                                   value="{{ old('reason', $customerReturn->reason) }}" 
                                   placeholder="Enter return reason" required>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes (optional)">{{ old('notes', $customerReturn->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Items Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-boxes me-2"></i>Return Items</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                                <i class="fas fa-plus me-1"></i>Add Item
                            </button>
                        </div>
                        
                        <div id="itemsContainer">
                            @foreach($customerReturn->transactionItems as $index => $transactionItem)
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Item</label>
                                    <select class="form-select item-select" name="items[{{ $index }}][item_id]" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" 
                                                    data-price="{{ $item->selling_price }}"
                                                    {{ $transactionItem->item_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->item_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control quantity-input" 
                                           name="items[{{ $index }}][quantity]" 
                                           value="{{ $transactionItem->quantity }}"
                                           step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price</label>
                                    <input type="number" class="form-control unit-price-input" 
                                           name="items[{{ $index }}][unit_price]" 
                                           value="{{ $transactionItem->unit_price }}"
                                           step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total Price</label>
                                    <input type="number" class="form-control total-price-input" 
                                           name="items[{{ $index }}][total_price]" 
                                           value="{{ $transactionItem->total_price }}"
                                           step="0.01" min="0" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-outline-danger d-block remove-item" 
                                            style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Return
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Return Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Return Number:</strong>
                    <p class="mb-0">{{ $customerReturn->return_number }}</p>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <p class="mb-0">
                        <span class="badge bg-{{ $customerReturn->status_badge }}">
                            {{ ucfirst($customerReturn->status) }}
                        </span>
                    </p>
                </div>
                <div class="mb-3">
                    <strong>Total Items:</strong> <span id="totalItems">{{ $customerReturn->transactionItems->count() }}</span>
                </div>
                <div class="mb-3">
                    <strong>Total Quantity:</strong> <span id="totalQuantity">{{ $customerReturn->transactionItems->sum('quantity') }}</span>
                </div>
                <div class="mb-3">
                    <strong>Total Amount:</strong> <span id="totalAmount">{{ number_format($customerReturn->total_amount, 2) }}</span> <span id="currencyCode">{{ $customerReturn->currency->code ?? 'LKR' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ $customerReturn->transactionItems->count() - 1 }};
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItem');
    
    // Add item row
    addItemBtn.addEventListener('click', function() {
        itemIndex++;
        const newRow = createItemRow(itemIndex);
        itemsContainer.appendChild(newRow);
        updateRemoveButtons();
    });
    
    // Remove item row
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
            updateSummary();
        }
    });
    
    // Handle item selection and price calculation
    itemsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select')) {
            const selectedOption = e.target.selectedOptions[0];
            const unitPriceInput = e.target.closest('.item-row').querySelector('.unit-price-input');
            if (selectedOption && selectedOption.dataset.price) {
                unitPriceInput.value = selectedOption.dataset.price;
                calculateTotal(e.target.closest('.item-row'));
            }
        }
        
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('unit-price-input')) {
            calculateTotal(e.target.closest('.item-row'));
        }
    });
    
    function createItemRow(index) {
        const row = document.createElement('div');
        row.className = 'item-row row mb-3';
        row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Item</label>
                <select class="form-select item-select" name="items[${index}][item_id]" required>
                    <option value="">Select Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->selling_price }}">
                            {{ $item->name }} ({{ $item->item_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control quantity-input" 
                       name="items[${index}][quantity]" step="0.01" min="0.01" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Unit Price</label>
                <input type="number" class="form-control unit-price-input" 
                       name="items[${index}][unit_price]" step="0.01" min="0" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Total Price</label>
                <input type="number" class="form-control total-price-input" 
                       name="items[${index}][total_price]" step="0.01" min="0" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-sm btn-outline-danger d-block remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        return row;
    }
    
    function calculateTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        const totalPrice = quantity * unitPrice;
        
        row.querySelector('.total-price-input').value = totalPrice.toFixed(2);
        updateSummary();
    }
    
    function updateSummary() {
        const rows = itemsContainer.querySelectorAll('.item-row');
        let totalItems = 0;
        let totalQuantity = 0;
        let totalAmount = 0;
        
        rows.forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const totalPrice = parseFloat(row.querySelector('.total-price-input').value) || 0;
            
            if (quantity > 0) {
                totalItems++;
                totalQuantity += quantity;
                totalAmount += totalPrice;
            }
        });
        
        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalQuantity').textContent = totalQuantity.toFixed(2);
        document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
    }
    
    function updateRemoveButtons() {
        const rows = itemsContainer.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('.remove-item');
            removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
        });
    }
    
    // Update currency code when currency changes
    document.getElementById('currency_id').addEventListener('change', function() {
        const selectedOption = this.selectedOptions[0];
        if (selectedOption) {
            const currencyCode = selectedOption.textContent.split(' - ')[0];
            document.getElementById('currencyCode').textContent = currencyCode;
            
            // Convert all item prices when currency changes
            convertItemPrices(selectedOption.value);
        }
    });
    
    // Function to convert item prices
    function convertItemPrices(newCurrencyId) {
        // Get current currency from the form
        const currentCurrencySelect = document.getElementById('currency_id');
        const currentCurrencyId = currentCurrencySelect.dataset.originalCurrencyId || '{{ $customerReturn->currency_id }}';
        
        if (currentCurrencyId === newCurrencyId) {
            return; // No conversion needed
        }
        
        // Store the original currency ID for future reference
        currentCurrencySelect.dataset.originalCurrencyId = newCurrencyId;
        
        // Fetch exchange rates from backend
        fetch('/customer-returns/exchange-rates')
            .then(response => response.json())
            .then(exchangeRates => {
                const fromCurrency = exchangeRates[currentCurrencyId];
                const toCurrency = exchangeRates[newCurrencyId];
                
                if (!fromCurrency || !toCurrency) {
                    console.error('Currency not found');
                    return;
                }
                
                // Convert all unit prices and total prices
                const rows = itemsContainer.querySelectorAll('.item-row');
                rows.forEach(row => {
                    const unitPriceInput = row.querySelector('.unit-price-input');
                    const totalPriceInput = row.querySelector('.total-price-input');
                    const quantityInput = row.querySelector('.quantity-input');
                    
                    if (unitPriceInput.value) {
                        // Convert unit price
                        const originalUnitPrice = parseFloat(unitPriceInput.value);
                        const convertedUnitPrice = convertCurrency(originalUnitPrice, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                        unitPriceInput.value = convertedUnitPrice.toFixed(2);
                        
                        // Recalculate total price
                        const quantity = parseFloat(quantityInput.value) || 0;
                        const totalPrice = quantity * convertedUnitPrice;
                        totalPriceInput.value = totalPrice.toFixed(2);
                    }
                });
                
                updateSummary();
            })
            .catch(error => {
                console.error('Error fetching exchange rates:', error);
            });
    }
    
    // Helper function to convert currency
    function convertCurrency(amount, fromRate, toRate) {
        // Convert to LKR first, then to target currency
        // Exchange rates are stored as "1 unit of currency = X LKR"
        const lkrAmount = amount * fromRate;
        return lkrAmount / toRate;
    }
    
    // Handle form submission to ensure converted values are submitted
    document.getElementById('returnForm').addEventListener('submit', function(e) {
        // Ensure all converted values are properly set before submission
        const rows = itemsContainer.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            const unitPriceInput = row.querySelector('.unit-price-input');
            const totalPriceInput = row.querySelector('.total-price-input');
            
            // Make sure the values are properly formatted
            if (unitPriceInput.value) {
                unitPriceInput.value = parseFloat(unitPriceInput.value).toFixed(2);
            }
            if (totalPriceInput.value) {
                totalPriceInput.value = parseFloat(totalPriceInput.value).toFixed(2);
            }
        });
    });

    // Initialize
    updateRemoveButtons();
    updateSummary();
});
</script>
@endsection
