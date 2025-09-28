@extends('layouts.app')

@section('title', 'Edit Item Transfer')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Item Transfer</h1>
        <div>
            <a href="{{ route('item-transfers.show', $itemTransfer) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transfer
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Transfer Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transfer Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('item-transfers.update', $itemTransfer) }}" id="transferForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                <select class="form-control @error('item_id') is-invalid @enderror" id="item_id" name="item_id" required>
                                    <option value="">Select Item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stock="{{ $item->current_stock }}"
                                                data-code="{{ $item->item_code }}"
                                                {{ old('item_id', $itemTransfer->item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }}) - Stock: {{ $item->current_stock }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" 
                                       value="{{ $itemTransfer->reference_number }}" readonly>
                                <small class="form-text text-muted">Reference number cannot be changed</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="from_location" class="form-label">From Location <span class="text-danger">*</span></label>
                                <select class="form-control @error('from_location') is-invalid @enderror" id="from_location" name="from_location" required>
                                    <option value="">Select From Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ old('from_location', $itemTransfer->from_location) == $location ? 'selected' : '' }}>
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="to_location" class="form-label">To Location <span class="text-danger">*</span></label>
                                <select class="form-control @error('to_location') is-invalid @enderror" id="to_location" name="to_location" required>
                                    <option value="">Select To Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ old('to_location', $itemTransfer->to_location) == $location ? 'selected' : '' }}>
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" min="0.001" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" 
                                       value="{{ old('quantity', $itemTransfer->quantity) }}" required>
                                <div class="form-text">
                                    Available Stock: <span id="availableStock" class="text-muted">Select an item</span>
                                </div>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="transfer_date" class="form-label">Transfer Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('transfer_date') is-invalid @enderror" 
                                       id="transfer_date" name="transfer_date" 
                                       value="{{ old('transfer_date', $itemTransfer->transfer_date->format('Y-m-d')) }}" required>
                                @error('transfer_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                <select class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                    <option value="">Select Reason</option>
                                    @foreach($reasons as $value => $label)
                                        <option value="{{ $value }}" {{ old('reason', $itemTransfer->reason) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transferred_by" class="form-label">Transferred By</label>
                                <select class="form-control @error('transferred_by') is-invalid @enderror" id="transferred_by" name="transferred_by">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('transferred_by', $itemTransfer->transferred_by) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('transferred_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes about this transfer...">{{ old('notes', $itemTransfer->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('item-transfers.show', $itemTransfer) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Transfer Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Transfer Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Status</h6>
                        <span class="badge badge-{{ $itemTransfer->status_color }} badge-lg">
                            {{ $itemTransfer->status_label }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Transfer Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Reference:</td>
                                <td><strong>{{ $itemTransfer->reference_number }}</strong></td>
                            </tr>
                            <tr>
                                <td>Item:</td>
                                <td><strong>{{ $itemTransfer->item->name }}</strong></td>
                            </tr>
                            <tr>
                                <td>From:</td>
                                <td><span class="text-danger">{{ $itemTransfer->from_location }}</span></td>
                            </tr>
                            <tr>
                                <td>To:</td>
                                <td><span class="text-success">{{ $itemTransfer->to_location }}</span></td>
                            </tr>
                            <tr>
                                <td>Quantity:</td>
                                <td><strong>{{ number_format($itemTransfer->quantity, 3) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>{{ $itemTransfer->transfer_date->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    @if($itemTransfer->is_overdue)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Overdue:</strong> This transfer is {{ $itemTransfer->days_overdue }} days overdue.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stock Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Information</h6>
                </div>
                <div class="card-body">
                    <div id="stockInfo" class="text-center">
                        <i class="fas fa-cubes fa-2x text-primary mb-2"></i>
                        <h6 class="text-primary">{{ $itemTransfer->item->name }}</h6>
                        <p class="text-muted mb-1">Item Code: {{ $itemTransfer->item->item_code }}</p>
                        <p class="text-success font-weight-bold">Available Stock: {{ $itemTransfer->item->current_stock }}</p>
                        <p class="text-info">Current Transfer: {{ number_format($itemTransfer->quantity, 3) }}</p>
                    </div>
                </div>
            </div>

            <!-- Edit Restrictions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Restrictions</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Reference number cannot be changed</li>
                            <li>Completed transfers cannot be edited</li>
                            <li>Quantity changes will affect stock calculations</li>
                            <li>Status changes should be done from the transfer details page</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const availableStockSpan = document.getElementById('availableStock');
    const stockInfoDiv = document.getElementById('stockInfo');
    const originalQuantity = {{ $itemTransfer->quantity }};

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const stock = selectedOption.dataset.stock;
            const code = selectedOption.dataset.code;
            
            availableStockSpan.textContent = stock;
            availableStockSpan.className = 'text-success font-weight-bold';
            
            // Update stock info card
            stockInfoDiv.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-cubes fa-2x text-primary mb-2"></i>
                    <h6 class="text-primary">${selectedOption.textContent.split(' (')[0]}</h6>
                    <p class="text-muted mb-1">Item Code: ${code}</p>
                    <p class="text-success font-weight-bold">Available Stock: ${stock}</p>
                    <p class="text-info">Current Transfer: ${originalQuantity}</p>
                </div>
            `;
            
            // Set max quantity
            quantityInput.max = stock;
        } else {
            availableStockSpan.textContent = 'Select an item';
            availableStockSpan.className = 'text-muted';
        }
    });

    // Initialize on page load
    itemSelect.dispatchEvent(new Event('change'));

    // Validate quantity against available stock
    quantityInput.addEventListener('input', function() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        if (selectedOption.value) {
            const maxStock = parseFloat(selectedOption.dataset.stock);
            const enteredQuantity = parseFloat(this.value);
            
            if (enteredQuantity > maxStock) {
                this.setCustomValidity(`Quantity cannot exceed available stock (${maxStock})`);
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        }
    });

    // Validate form before submission
    document.getElementById('transferForm').addEventListener('submit', function(e) {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        if (selectedOption.value) {
            const maxStock = parseFloat(selectedOption.dataset.stock);
            const enteredQuantity = parseFloat(quantityInput.value);
            
            if (enteredQuantity > maxStock) {
                e.preventDefault();
                alert(`Quantity cannot exceed available stock (${maxStock})`);
                quantityInput.focus();
                return false;
            }
        }
    });
});
</script>
@endpush
