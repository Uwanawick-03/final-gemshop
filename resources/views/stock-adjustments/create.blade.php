@extends('layouts.app')

@section('title', 'Create Stock Adjustment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-adjust me-2"></i>Create Stock Adjustment</h4>
        <div class="small text-muted">Adjust inventory quantities for various reasons</div>
    </div>
    <a href="{{ route('stock-adjustments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('stock-adjustments.store') }}" id="stockAdjustmentForm">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Adjustment Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Adjustment Date <span class="text-danger">*</span></label>
                    <input type="date" name="adjustment_date" value="{{ old('adjustment_date', now()->format('Y-m-d')) }}" 
                           class="form-control @error('adjustment_date') is-invalid @enderror" required>
                    @error('adjustment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select type</option>
                        <option value="increase" {{ old('type') == 'increase' ? 'selected' : '' }}>Stock Increase</option>
                        <option value="decrease" {{ old('type') == 'decrease' ? 'selected' : '' }}>Stock Decrease</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select @error('reason') is-invalid @enderror" required>
                        <option value="">Select reason</option>
                        @foreach($reasons as $key => $label)
                            <option value="{{ $key }}" {{ old('reason') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" 
                              placeholder="Additional notes about this adjustment...">{{ old('notes') }}</textarea>
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
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Items to Adjust</h5>
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
                            <th width="15%">Current Qty</th>
                            <th width="15%">Adjusted Qty</th>
                            <th width="15%">Difference</th>
                            <th width="15%">Unit Cost</th>
                            <th width="10%">Reason</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <!-- Items will be added here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Total Items:</th>
                            <th id="totalItems">0</th>
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
                <a href="{{ route('stock-adjustments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Create Stock Adjustment
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                    ${items.map(item => `<option value="${item.id}" data-current-qty="${item.current_stock}" data-unit-cost="${item.cost_price || 0}">${item.item_code} - ${item.name}</option>`).join('')}
                </select>
                <input type="hidden" name="items[${itemCounter}][item_code]" class="item-code">
                <input type="hidden" name="items[${itemCounter}][item_name]" class="item-name">
            </td>
            <td>
                <input type="number" class="form-control current-quantity" readonly value="0" step="0.01">
            </td>
            <td>
                <input type="number" name="items[${itemCounter}][adjusted_quantity]" class="form-control adjusted-quantity" 
                       min="0" step="0.01" value="0" required>
            </td>
            <td>
                <input type="number" class="form-control difference-quantity" readonly value="0" step="0.01">
            </td>
            <td>
                <input type="number" class="form-control unit-cost" readonly value="0" step="0.01">
            </td>
            <td>
                <input type="text" name="items[${itemCounter}][reason]" class="form-control item-reason" 
                       placeholder="Item reason">
                <textarea name="items[${itemCounter}][notes]" class="form-control mt-1" rows="2" 
                          placeholder="Notes"></textarea>
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
        const adjustedQuantity = row.querySelector('.adjusted-quantity');
        
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const currentQty = selectedOption.getAttribute('data-current-qty') || 0;
            const unitCost = selectedOption.getAttribute('data-unit-cost') || 0;
            
            row.querySelector('.current-quantity').value = currentQty;
            row.querySelector('.unit-cost').value = unitCost;
            row.querySelector('.item-code').value = selectedOption.text.split(' - ')[0];
            row.querySelector('.item-name').value = selectedOption.text.split(' - ')[1] || '';
            
            calculateDifference(row);
        });
        
        adjustedQuantity.addEventListener('input', function() {
            calculateDifference(row);
        });
    }
    
    window.removeItemRow = function(rowId) {
        const row = document.getElementById(`itemRow${rowId}`);
        if (row) {
            row.remove();
            updateTotalItems();
        }
    };
    
    function calculateDifference(row) {
        const currentQty = parseFloat(row.querySelector('.current-quantity').value) || 0;
        const adjustedQty = parseFloat(row.querySelector('.adjusted-quantity').value) || 0;
        const difference = adjustedQty - currentQty;
        
        row.querySelector('.difference-quantity').value = difference;
        updateTotalItems();
    }
    
    function updateTotalItems() {
        const itemRows = document.querySelectorAll('#itemsTableBody tr');
        document.getElementById('totalItems').textContent = itemRows.length;
    }
    
    // Form validation
    document.getElementById('stockAdjustmentForm').addEventListener('submit', function(e) {
        const itemRows = document.querySelectorAll('#itemsTableBody tr');
        let hasValidItem = false;
        
        itemRows.forEach(row => {
            const itemSelect = row.querySelector('.item-select');
            const adjustedQuantity = row.querySelector('.adjusted-quantity');
            
            if (itemSelect.value && adjustedQuantity.value > 0) {
                hasValidItem = true;
            }
        });
        
        if (!hasValidItem) {
            e.preventDefault();
            alert('Please add at least one item with adjusted quantity.');
            return false;
        }
    });
});
</script>
@endsection
