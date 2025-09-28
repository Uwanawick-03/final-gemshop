@extends('layouts.app')

@section('title', 'Supplier Return Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Supplier Return Details</h1>
                    <p class="text-muted">Return #{{ $supplierReturn->return_number }}</p>
                </div>
                <div>
                    <div class="btn-group" role="group">
                        <a href="{{ route('supplier-returns.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Returns
                        </a>
                        
                        @if($supplierReturn->status === 'pending')
                            <a href="{{ route('supplier-returns.edit', $supplierReturn) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        @endif
                        
                        <a href="{{ route('supplier-returns.export-pdf', $supplierReturn) }}" class="btn btn-outline-info">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                        
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @if($supplierReturn->status === 'pending')
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('supplier-returns.destroy', $supplierReturn) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this supplier return?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Return Information -->
        <div class="col-lg-8">
            <!-- Return Header -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Return Information</h6>
                        <div>
                            <span class="badge bg-{{ $supplierReturn->status_color }} fs-6">
                                {{ $supplierReturn->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Supplier:</h6>
                            <p class="mb-1"><strong>{{ $supplierReturn->supplier->company_name }}</strong></p>
                            <p class="mb-1">{{ $supplierReturn->supplier->supplier_code }}</p>
                            @if($supplierReturn->supplier->contact_person)
                                <p class="mb-1">Contact: {{ $supplierReturn->supplier->contact_person }}</p>
                            @endif
                            @if($supplierReturn->supplier->email)
                                <p class="mb-1">{{ $supplierReturn->supplier->email }}</p>
                            @endif
                            @if($supplierReturn->supplier->phone)
                                <p class="mb-1">{{ $supplierReturn->supplier->phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Return Details:</h6>
                            <p class="mb-1"><strong>Return #:</strong> {{ $supplierReturn->return_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $supplierReturn->return_date->format('M d, Y') }}</p>
                            <p class="mb-1"><strong>Reason:</strong> 
                                <span class="badge bg-{{ $supplierReturn->reason_color }}">
                                    {{ $supplierReturn->reason_label }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Created By:</strong> {{ $supplierReturn->createdBy?->name ?? 'Unknown' }}</p>
                            <p class="mb-1"><strong>Created:</strong> {{ $supplierReturn->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Return Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Tax</th>
                                    <th width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplierReturn->transactionItems as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->item->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->item->item_code }}</small>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->quantity, 3) }}</td>
                                        <td>
                                            <span class="item-unit-price">{{ number_format($item->unit_price, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($item->discount_percentage > 0)
                                                {{ number_format($item->discount_percentage, 1) }}%
                                                <br>
                                                <small class="text-muted">({{ number_format($item->discount_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tax_percentage > 0)
                                                {{ number_format($item->tax_percentage, 1) }}%
                                                <br>
                                                <small class="text-muted">({{ number_format($item->tax_amount, 2) }})</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="item-total-price">{{ number_format($item->total_price, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($supplierReturn->notes)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $supplierReturn->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Return Summary -->
        <div class="col-lg-4">
            <!-- Status Management -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Management</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier-returns.update-status', $supplierReturn) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Current Status</label>
                            <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                <option value="pending" {{ $supplierReturn->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $supplierReturn->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="completed" {{ $supplierReturn->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $supplierReturn->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Return Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Return Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="displaySubtotal">{{ number_format($supplierReturn->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span id="displayDiscount">{{ number_format($supplierReturn->discount_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span id="displayTax">{{ number_format($supplierReturn->tax_amount, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong id="displayTotalAmount">{{ number_format($supplierReturn->total_amount, 2) }}</strong>
                    </div>
                    <div class="text-muted small mt-1">
                        Currency: <span id="displayCurrencyCode">{{ $supplierReturn->currency->code }}</span>
                    </div>
                </div>
            </div>

            <!-- Return Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Return Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Return Created</h6>
                                <p class="timeline-text">{{ $supplierReturn->created_at->format('M d, Y H:i') }}</p>
                                @if($supplierReturn->createdBy)
                                    <small class="text-muted">by {{ $supplierReturn->createdBy->name }}</small>
                                @endif
                            </div>
                        </div>
                        
                        @if($supplierReturn->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Return Approved</h6>
                                    <p class="timeline-text">{{ $supplierReturn->approved_at->format('M d, Y H:i') }}</p>
                                    @if($supplierReturn->approvedBy)
                                        <small class="text-muted">by {{ $supplierReturn->approvedBy->name }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($supplierReturn->processedBy)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Return Processed</h6>
                                    <p class="timeline-text">{{ $supplierReturn->updated_at->format('M d, Y H:i') }}</p>
                                    <small class="text-muted">by {{ $supplierReturn->processedBy->name }}</small>
                                </div>
                            </div>
                        @endif
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
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0 0 5px 0;
    font-size: 13px;
    color: #6c757d;
}
</style>

<script>
// Currency conversion functionality
function updateCurrencyDisplay(newCurrency) {
    console.log('Updating currency display to:', newCurrency);
    
    // Update all amount displays
    const amountElements = document.querySelectorAll('.item-unit-price, .item-total-price');
    const currencyElements = document.querySelectorAll('#displayCurrencyCode');
    
    // Convert unit prices and totals
    amountElements.forEach(element => {
        const originalAmount = parseFloat(element.textContent.replace(/,/g, ''));
        const originalCurrency = '{{ $supplierReturn->currency->code }}';
        
        if (originalCurrency !== newCurrency) {
            // Convert amount
            fetch(`{{ route('supplier-returns.exchange-rates') }}`)
                .then(response => response.json())
                .then(data => {
                    const fromCurrency = data.currencies.find(c => c.code === originalCurrency);
                    const toCurrency = data.currencies.find(c => c.code === newCurrency);
                    
                    if (fromCurrency && toCurrency) {
                        const convertedAmount = (originalAmount * fromCurrency.exchange_rate) / toCurrency.exchange_rate;
                        element.textContent = convertedAmount.toFixed(2);
                    }
                })
                .catch(error => console.error('Error converting currency:', error));
        }
    });
    
    // Update summary amounts
    const summaryElements = {
        subtotal: document.getElementById('displaySubtotal'),
        discount: document.getElementById('displayDiscount'),
        tax: document.getElementById('displayTax'),
        total: document.getElementById('displayTotalAmount')
    };
    
    const originalAmounts = {
        subtotal: {{ $supplierReturn->subtotal }},
        discount: {{ $supplierReturn->discount_amount }},
        tax: {{ $supplierReturn->tax_amount }},
        total: {{ $supplierReturn->total_amount }}
    };
    
    const originalCurrency = '{{ $supplierReturn->currency->code }}';
    
    if (originalCurrency !== newCurrency) {
        fetch(`{{ route('supplier-returns.exchange-rates') }}`)
            .then(response => response.json())
            .then(data => {
                const fromCurrency = data.currencies.find(c => c.code === originalCurrency);
                const toCurrency = data.currencies.find(c => c.code === newCurrency);
                
                if (fromCurrency && toCurrency) {
                    Object.keys(summaryElements).forEach(key => {
                        const convertedAmount = (originalAmounts[key] * fromCurrency.exchange_rate) / toCurrency.exchange_rate;
                        summaryElements[key].textContent = convertedAmount.toFixed(2);
                    });
                    document.getElementById('displayCurrencyCode').textContent = newCurrency;
                }
            })
            .catch(error => console.error('Error converting summary amounts:', error));
    }
}

// Listen for currency display changes
const currencyDisplayDropdown = document.getElementById('currentDisplayCurrency');
if (currencyDisplayDropdown) {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                const newCurrency = mutation.target.textContent.trim();
                updateCurrencyDisplay(newCurrency);
            }
        });
    });
    
    observer.observe(currencyDisplayDropdown, {
        childList: true,
        characterData: true,
        subtree: true
    });
}

// Auto-refresh page after status update
@if(session('success'))
    setTimeout(function() {
        window.location.reload();
    }, 2000);
@endif
</script>
@endsection
