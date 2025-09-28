@extends('layouts.app')

@section('title', 'Customer Returns Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-undo me-2"></i>Customer Returns Management</h2>
    <a href="{{ route('customer-returns.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Return
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-undo fa-2x text-primary mb-2"></i>
                <h4 class="mb-1">{{ $customerReturns->total() }}</h4>
                <small class="text-muted">Total Returns</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h4 class="mb-1">{{ $customerReturns->where('status', 'pending')->count() }}</h4>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h4 class="mb-1">{{ $customerReturns->where('status', 'approved')->count() }}</h4>
                <small class="text-muted">Approved</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                <h4 class="mb-1" id="totalValueAmount">{{ number_format($totalValueInLKR, 2) }}</h4>
                <small class="text-muted">Total Value (<span id="totalValueCurrency">LKR</span>)</small>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Search & Filter</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('customer-returns.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by return number, reason, or customer...">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="customer_id">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_from" 
                       value="{{ request('date_from') }}" 
                       placeholder="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_to" 
                       value="{{ request('date_to') }}" 
                       placeholder="To Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Customer Returns Table -->
<div class="card">
    <div class="card-body">
        @if($customerReturns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Return Number</th>
                            <th>Customer</th>
                            <th>Return Date</th>
                            <th>Reason</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customerReturns as $return)
                        <tr data-return-id="{{ $return->id }}">
                            <td>
                                <strong>{{ $return->return_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $return->customer->full_name }}</strong>
                                    <br><small class="text-muted">{{ $return->customer->customer_code }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $return->return_date->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $return->reason }}">
                                    {{ $return->reason }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">
                                    <span class="return-total-amount">{{ number_format($return->total_amount, 2) }}</span> 
                                    <span class="return-currency">{{ $return->currency->code ?? 'LKR' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $return->status_badge }}">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customer-returns.show', $return) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($return->status === 'pending')
                                        <a href="{{ route('customer-returns.edit', $return) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customer-returns.destroy', $return) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this return?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $customerReturns->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customer returns found</h5>
                <p class="text-muted">Start by creating your first customer return.</p>
                <a href="{{ route('customer-returns.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Return
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store original return data
    const returnData = [
        @foreach($customerReturns as $return)
        {
            id: {{ $return->id }},
            totalAmount: {{ $return->total_amount }},
            currency: '{{ $return->currency->code }}'
        },
        @endforeach
    ];

    // Calculate total value in a specific currency (only for display currency changes)
    function calculateTotalValue(targetCurrency = 'LKR') {
        return fetch('/customer-returns/exchange-rates')
            .then(response => response.json())
            .then(exchangeRates => {
                const currencies = Object.values(exchangeRates);
                let totalValue = 0;
                
                returnData.forEach(returnInfo => {
                    const fromCurrency = currencies.find(c => c.code === returnInfo.currency);
                    const toCurrency = currencies.find(c => c.code === targetCurrency);
                    
                    if (fromCurrency && toCurrency) {
                        const convertedAmount = convertCurrency(returnInfo.totalAmount, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                        totalValue += convertedAmount;
                    } else {
                        totalValue += returnInfo.totalAmount;
                    }
                });
                
                return { totalValue, currency: targetCurrency };
            })
            .catch(error => {
                console.error('Error calculating total value:', error);
                return { totalValue: 0, currency: targetCurrency };
            });
    }

    // Function to convert currency
    function convertCurrency(amount, fromRate, toRate) {
        const lkrAmount = amount * fromRate;
        return lkrAmount / toRate;
    }

    // Function to update display when currency changes
    function updateCurrencyDisplay(newCurrencyCode) {
        fetch('/customer-returns/exchange-rates')
            .then(response => response.json())
            .then(exchangeRates => {
                const currencies = Object.values(exchangeRates);
                
                returnData.forEach(returnInfo => {
                    const fromCurrency = currencies.find(c => c.code === returnInfo.currency);
                    const toCurrency = currencies.find(c => c.code === newCurrencyCode);
                    
                    if (fromCurrency && toCurrency) {
                        const convertedAmount = convertCurrency(returnInfo.totalAmount, fromCurrency.exchange_rate, toCurrency.exchange_rate);
                        
                        // Update the display for this return
                        const returnRow = document.querySelector(`tr[data-return-id="${returnInfo.id}"]`);
                        if (returnRow) {
                            const amountElement = returnRow.querySelector('.return-total-amount');
                            const currencyElement = returnRow.querySelector('.return-currency');
                            
                            if (amountElement) amountElement.textContent = convertedAmount.toFixed(2);
                            if (currencyElement) currencyElement.textContent = newCurrencyCode;
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching exchange rates:', error);
            });
    }

    // Update total value when currency changes
    function updateTotalValue(targetCurrency) {
        calculateTotalValue(targetCurrency).then(result => {
            document.getElementById('totalValueAmount').textContent = result.totalValue.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            document.getElementById('totalValueCurrency').textContent = result.currency;
        });
    }

    // Don't initialize total value - server already calculated it correctly

    // Listen for currency display changes
    const currencyDisplayDropdown = document.getElementById('currentDisplayCurrency');
    if (currencyDisplayDropdown) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    const newCurrency = mutation.target.textContent.trim();
                    updateCurrencyDisplay(newCurrency);
                    // Only update total value if currency is not LKR (server calculated in LKR)
                    if (newCurrency !== 'LKR') {
                        updateTotalValue(newCurrency);
                    }
                }
            });
        });
        
        observer.observe(currencyDisplayDropdown, {
            childList: true,
            characterData: true,
            subtree: true
        });
    }
});
</script>
@endsection
