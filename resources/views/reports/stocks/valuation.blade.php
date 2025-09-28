@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.stocks') }}">Stocks Report</a></li>
                        <li class="breadcrumb-item active">Stock Valuation</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-calculator me-2"></i>Stock Valuation Report
                </h4>
            </div>
        </div>
    </div>

    <!-- Valuation Method Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Valuation Method</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.stocks.valuation') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="method" class="form-label">Valuation Method</label>
                                <select class="form-select" id="method" name="method" onchange="this.form.submit()">
                                    <option value="cost" {{ $valuationMethod == 'cost' ? 'selected' : '' }}>Cost Price</option>
                                    <option value="selling" {{ $valuationMethod == 'selling' ? 'selected' : '' }}>Selling Price</option>
                                    <option value="wholesale" {{ $valuationMethod == 'wholesale' ? 'selected' : '' }}>Wholesale Price</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Description</label>
                                <div class="alert alert-info mb-0">
                                    @switch($valuationMethod)
                                        @case('cost')
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Cost Price Valuation:</strong> Items are valued at their cost price. This represents the actual investment in inventory.
                                        @break
                                        @case('selling')
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Selling Price Valuation:</strong> Items are valued at their selling price. This represents the potential revenue from inventory.
                                        @break
                                        @case('wholesale')
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Wholesale Price Valuation:</strong> Items are valued at their wholesale price. This represents the bulk selling value of inventory.
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Items</p>
                            <h4 class="mb-0">{{ number_format($totalItems) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-primary font-size-18">
                                    <i class="fas fa-boxes"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Stock Quantity</p>
                            <h4 class="mb-0">{{ number_format($totalStock) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-cubes"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Value</p>
                            <h4 class="mb-0 text-success">${{ number_format($totalValue, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-success font-size-18">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Average Value per Item</p>
                            <h4 class="mb-0 text-warning">${{ number_format($totalItems > 0 ? $totalValue / $totalItems : 0, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Stock Valuation Report</h5>
                            <small class="text-muted">Valuation Method: {{ ucfirst($valuationMethod) }} Price</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.stocks.export-pdf', ['type' => 'valuation', 'method' => $valuationMethod]) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.stocks.export-excel', ['type' => 'valuation', 'method' => $valuationMethod]) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.stocks.export-csv', ['type' => 'valuation', 'method' => $valuationMethod]) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valuation Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Material</th>
                                    <th>Stock Qty</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Wholesale Price</th>
                                    <th>Valuation Price</th>
                                    <th>Total Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        <code>{{ $item->item_code }}</code>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->name }}</strong>
                                            @if($item->description)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->category }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->material }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->stock_status_color }} fs-6">
                                            {{ number_format($item->current_stock) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($item->cost_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($item->selling_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($item->wholesale_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-{{ $valuationMethod == 'cost' ? 'primary' : ($valuationMethod == 'selling' ? 'success' : 'info') }}">
                                            ${{ number_format($item->valuation_price, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($item->total_value, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->stock_status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <p>No items found for valuation.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Analysis -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Value Analysis</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary">${{ number_format($items->where('total_value', '>', 0)->sum('total_value'), 2) }}</h4>
                                <p class="text-muted mb-0">Total Value ({{ ucfirst($valuationMethod) }})</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-success">${{ number_format($items->where('total_value', '>', 0)->avg('total_value'), 2) }}</h4>
                                <p class="text-muted mb-0">Average Value per Item</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info">${{ number_format($items->where('total_value', '>', 0)->max('total_value'), 2) }}</h4>
                                <p class="text-muted mb-0">Highest Value Item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Value Items -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 10 Highest Value Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Item</th>
                                    <th>Stock</th>
                                    <th>Valuation Price</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items->take(10) as $index => $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->item_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->stock_status_color }}">{{ $item->current_stock }}</span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($item->valuation_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($item->total_value, 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add sorting functionality to the table
document.addEventListener('DOMContentLoaded', function() {
    // Simple table sorting (you might want to use a more robust solution like DataTables)
    const table = document.querySelector('table');
    if (table) {
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                // Simple sorting logic would go here
                console.log('Sort by column', index);
            });
        });
    }
});
</script>
@endpush
