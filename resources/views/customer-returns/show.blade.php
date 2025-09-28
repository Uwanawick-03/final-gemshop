@extends('layouts.app')

@section('title', 'Customer Return Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-undo me-2"></i>Customer Return Details</h2>
    <div>
        <a href="{{ route('customer-returns.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back to Returns
        </a>
        @if($customerReturn->status === 'pending')
            <a href="{{ route('customer-returns.edit', $customerReturn) }}" class="btn btn-outline-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
        @endif
        <a href="{{ route('customer-returns.export-pdf', $customerReturn) }}" class="btn btn-outline-info" target="_blank">
            <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Return Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Return Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Return Number:</strong>
                            <p class="mb-0">{{ $customerReturn->return_number }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Return Date:</strong>
                            <p class="mb-0">{{ $customerReturn->return_date->format('M d, Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                <span class="badge bg-{{ $customerReturn->status_badge }}">
                                    {{ ucfirst($customerReturn->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Customer:</strong>
                            <p class="mb-0">
                                <a href="{{ route('customers.show', $customerReturn->customer) }}" class="text-decoration-none">
                                    {{ $customerReturn->customer->full_name }}
                                </a>
                                <br><small class="text-muted">{{ $customerReturn->customer->customer_code }}</small>
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong>Currency:</strong>
                            <p class="mb-0">{{ $customerReturn->currency->code ?? 'LKR' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Total Amount:</strong>
                            <p class="mb-0 h5 text-primary">
                                <span id="displayTotalAmount">{{ number_format($customerReturn->total_amount, 2) }}</span> 
                                <span id="displayCurrencyCode">{{ $customerReturn->currency->code ?? 'LKR' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <strong>Reason:</strong>
                            <p class="mb-0">{{ $customerReturn->reason }}</p>
                        </div>
                        @if($customerReturn->notes)
                            <div class="mb-3">
                                <strong>Notes:</strong>
                                <p class="mb-0">{{ $customerReturn->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Return Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerReturn->transactionItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item->item_code }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $item->item->name }}</strong>
                                        @if($item->item->description)
                                            <br><small class="text-muted">{{ $item->item->description }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($item->quantity, 2) }}</span>
                                </td>
                                <td>
                                    <span class="item-unit-price">{{ number_format($item->unit_price, 2) }}</span> 
                                    <span class="item-currency">{{ $customerReturn->currency->code ?? 'LKR' }}</span>
                                </td>
                                <td>
                                    <strong><span class="item-total-price">{{ number_format($item->total_price, 2) }}</span> 
                                    <span class="item-currency">{{ $customerReturn->currency->code ?? 'LKR' }}</span></strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="4" class="text-end">Total Amount:</th>
                                <th><span id="tableTotalAmount">{{ number_format($customerReturn->total_amount, 2) }}</span> 
                                <span id="tableCurrencyCode">{{ $customerReturn->currency->code ?? 'LKR' }}</span></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Management -->
        @if($customerReturn->status === 'pending')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Status Management</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customer-returns.update-status', $customerReturn) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong>
                    <p class="mb-0">{{ $customerReturn->customer->full_name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Customer Code:</strong>
                    <p class="mb-0">{{ $customerReturn->customer->customer_code }}</p>
                </div>
                @if($customerReturn->customer->email)
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">
                            <a href="mailto:{{ $customerReturn->customer->email }}" class="text-decoration-none">
                                {{ $customerReturn->customer->email }}
                            </a>
                        </p>
                    </div>
                @endif
                @if($customerReturn->customer->phone)
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">
                            <a href="tel:{{ $customerReturn->customer->phone }}" class="text-decoration-none">
                                {{ $customerReturn->customer->phone }}
                            </a>
                        </p>
                    </div>
                @endif
                @if($customerReturn->customer->full_address)
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p class="mb-0">{{ $customerReturn->customer->full_address }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Return History -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Return History</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Created:</strong>
                    <p class="mb-0">{{ $customerReturn->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($customerReturn->updated_at && $customerReturn->updated_at != $customerReturn->created_at)
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <p class="mb-0">{{ $customerReturn->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
                @if($customerReturn->createdBy)
                    <div class="mb-3">
                        <strong>Created By:</strong>
                        <p class="mb-0">{{ $customerReturn->createdBy->name }}</p>
                    </div>
                @endif
                @if($customerReturn->updatedBy)
                    <div class="mb-3">
                        <strong>Updated By:</strong>
                        <p class="mb-0">{{ $customerReturn->updatedBy->name }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we need to refresh the page after update
    @if(session('success'))
        // Small delay to show success message, then refresh
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    @endif
    
    // Store original amounts and currency
    let originalCurrency = '{{ $customerReturn->currency->code }}';
    let originalTotalAmount = {{ $customerReturn->total_amount }};
    let originalItemAmounts = [
        @foreach($customerReturn->transactionItems as $item)
        {
            unitPrice: {{ $item->unit_price }},
            totalPrice: {{ $item->total_price }}
        },
        @endforeach
    ];


    // Function to convert currency
    function convertCurrency(amount, fromRate, toRate) {
        const lkrAmount = amount * fromRate;
        return lkrAmount / toRate;
    }

    // Function to update display when currency changes
    function updateCurrencyDisplay(newCurrencyCode) {
        if (newCurrencyCode === originalCurrency) {
            // Show original amounts
            document.getElementById('displayTotalAmount').textContent = originalTotalAmount.toFixed(2);
            document.getElementById('displayCurrencyCode').textContent = originalCurrency;
            document.getElementById('tableTotalAmount').textContent = originalTotalAmount.toFixed(2);
            document.getElementById('tableCurrencyCode').textContent = originalCurrency;
            
            // Update item amounts
            const unitPriceElements = document.querySelectorAll('.item-unit-price');
            const totalPriceElements = document.querySelectorAll('.item-total-price');
            const currencyElements = document.querySelectorAll('.item-currency');
            
            originalItemAmounts.forEach((item, index) => {
                if (unitPriceElements[index]) {
                    unitPriceElements[index].textContent = item.unitPrice.toFixed(2);
                }
                if (totalPriceElements[index]) {
                    totalPriceElements[index].textContent = item.totalPrice.toFixed(2);
                }
                if (currencyElements[index]) {
                    currencyElements[index].textContent = originalCurrency;
                }
            });
            return;
        }

        // Fetch exchange rates and convert
        fetch('/customer-returns/exchange-rates')
            .then(response => response.json())
            .then(exchangeRates => {
                const fromCurrency = Object.values(exchangeRates).find(c => c.code === originalCurrency);
                const toCurrency = Object.values(exchangeRates).find(c => c.code === newCurrencyCode);
                
                if (!fromCurrency || !toCurrency) {
                    console.error('Currency not found');
                    return;
                }

                // Convert total amount
                const convertedTotal = convertCurrency(originalTotalAmount, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                document.getElementById('displayTotalAmount').textContent = convertedTotal.toFixed(2);
                document.getElementById('displayCurrencyCode').textContent = newCurrencyCode;
                document.getElementById('tableTotalAmount').textContent = convertedTotal.toFixed(2);
                document.getElementById('tableCurrencyCode').textContent = newCurrencyCode;

                // Convert item amounts
                const unitPriceElements = document.querySelectorAll('.item-unit-price');
                const totalPriceElements = document.querySelectorAll('.item-total-price');
                const currencyElements = document.querySelectorAll('.item-currency');
                
                originalItemAmounts.forEach((item, index) => {
                    const convertedUnitPrice = convertCurrency(item.unitPrice, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                    const convertedTotalPrice = convertCurrency(item.totalPrice, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                    
                    if (unitPriceElements[index]) {
                        unitPriceElements[index].textContent = convertedUnitPrice.toFixed(2);
                    }
                    if (totalPriceElements[index]) {
                        totalPriceElements[index].textContent = convertedTotalPrice.toFixed(2);
                    }
                    if (currencyElements[index]) {
                        currencyElements[index].textContent = newCurrencyCode;
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching exchange rates:', error);
            });
    }

    // Listen for currency display changes
    const currencyDisplayDropdown = document.getElementById('currentDisplayCurrency');
    if (currencyDisplayDropdown) {
        // This will be triggered when the currency display changes
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

    // Also listen for dropdown changes directly
    const currencyDropdown = document.getElementById('currencyDisplayDropdown');
    if (currencyDropdown) {
        currencyDropdown.addEventListener('click', function() {
            // Small delay to allow dropdown to update
            setTimeout(() => {
                const currentCurrency = document.getElementById('currentDisplayCurrency').textContent.trim();
                updateCurrencyDisplay(currentCurrency);
            }, 100);
        });
    }
});
</script>
@endsection
