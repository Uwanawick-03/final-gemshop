@extends('layouts.app')

@section('title', 'Create Item Transfer')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Item Transfer</h1>
        <div>
            <a href="{{ route('item-transfers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transfers
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
                    <form method="POST" action="{{ route('item-transfers.store') }}" id="transferForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                                <select class="form-control @error('item_id') is-invalid @enderror" id="item_id" name="item_id" required>
                                    <option value="">Select Item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stock="{{ $item->current_stock }}"
                                                data-code="{{ $item->item_code }}"
                                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
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
                                       value="Auto-generated" readonly>
                                <small class="form-text text-muted">Reference number will be auto-generated</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="from_location" class="form-label">From Location <span class="text-danger">*</span></label>
                                <select class="form-control @error('from_location') is-invalid @enderror" id="from_location" name="from_location" required>
                                    <option value="">Select From Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ old('from_location') == $location ? 'selected' : '' }}>
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
                                        <option value="{{ $location }}" {{ old('to_location') == $location ? 'selected' : '' }}>
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
                                       value="{{ old('quantity') }}" required>
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
                                       value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                @error('transfer_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                <select class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                    <option value="">Select Reason</option>
                                    @foreach($reasons as $value => $label)
                                        <option value="{{ $value }}" {{ old('reason') == $value ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('transferred_by') == $user->id ? 'selected' : '' }}>
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
                                      placeholder="Additional notes about this transfer...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('item-transfers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Transfer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transfer Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Status Flow</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pending</h6>
                                    <p class="timeline-text">Transfer is created and waiting to be processed</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">In Transit</h6>
                                    <p class="timeline-text">Items are being moved between locations</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Completed</h6>
                                    <p class="timeline-text">Transfer is completed and stock is updated</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Transfer Reasons</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge badge-success">Restock</span> - Restocking inventory</li>
                            <li><span class="badge badge-info">Sale Transfer</span> - Moving for sale</li>
                            <li><span class="badge badge-warning">Repair</span> - Sending for repair</li>
                            <li><span class="badge badge-primary">Display</span> - Moving to display</li>
                            <li><span class="badge badge-secondary">Storage</span> - Moving to storage</li>
                            <li><span class="badge badge-danger">Damage</span> - Damaged items</li>
                            <li><span class="badge badge-dark">Other</span> - Other reasons</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Once a transfer is completed, the item stock will be automatically updated.
                    </div>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Information</h6>
                </div>
                <div class="card-body">
                    <div id="stockInfo" class="text-center text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>Select an item to view stock information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const availableStockSpan = document.getElementById('availableStock');
    const stockInfoDiv = document.getElementById('stockInfo');

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
                </div>
            `;
            
            // Set max quantity
            quantityInput.max = stock;
        } else {
            availableStockSpan.textContent = 'Select an item';
            availableStockSpan.className = 'text-muted';
            stockInfoDiv.innerHTML = `
                <div class="text-center text-muted">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Select an item to view stock information</p>
                </div>
            `;
        }
    });

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
