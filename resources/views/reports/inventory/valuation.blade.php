@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.inventory') }}">Inventory Report</a></li>
                        <li class="breadcrumb-item active">Inventory Valuation</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-calculator me-2"></i>Inventory Valuation Report
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Valuation Method</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.inventory.valuation') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="method" class="form-label">Valuation Method</label>
                                <select class="form-select" id="method" name="method">
                                    <option value="cost" {{ $valuationMethod == 'cost' ? 'selected' : '' }}>Cost Price</option>
                                    <option value="selling" {{ $valuationMethod == 'selling' ? 'selected' : '' }}>Selling Price</option>
                                    <option value="wholesale" {{ $valuationMethod == 'wholesale' ? 'selected' : '' }}>Wholesale Price</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-calculator me-1"></i>Calculate
                                    </button>
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
                            <p class="text-truncate font-size-14 mb-2">Total Stock</p>
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
                            <h4 class="mb-0">${{ number_format($totalValue, 2) }}</h4>
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
                            <p class="text-truncate font-size-14 mb-2">Avg Value per Item</p>
                            <h4 class="mb-0">${{ number_format($totalItems > 0 ? $totalValue / $totalItems : 0, 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-chart-bar"></i>
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
                            <h5 class="mb-0">Inventory Valuation Report</h5>
                            <small class="text-muted">Valuation Method: {{ ucfirst($valuationMethod) }} Price</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.inventory.export-pdf', array_merge(request()->query(), ['type' => 'valuation'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.inventory.export-excel', array_merge(request()->query(), ['type' => 'valuation'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.inventory.export-csv', array_merge(request()->query(), ['type' => 'valuation'])) }}" 
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
                                    <th>Current Stock</th>
                                    <th>{{ ucfirst($valuationMethod) }} Price</th>
                                    <th>Total Value</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->item_code }}</strong>
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
                                    <td class="text-right">
                                        <strong>${{ number_format($item->valuation_price, 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">${{ number_format($item->total_value, 2) }}</strong>
                                    </td>
                                    <td class="text-right">${{ number_format($item->cost_price, 2) }}</td>
                                    <td class="text-right">${{ number_format($item->selling_price, 2) }}</td>
                                    <td class="text-right">
                                        @php
                                            $profitMargin = $item->cost_price > 0 ? (($item->selling_price - $item->cost_price) / $item->selling_price) * 100 : 0;
                                        @endphp
                                        <span class="badge bg-{{ $profitMargin > 0 ? 'success' : ($profitMargin < 0 ? 'danger' : 'secondary') }}">
                                            {{ number_format($profitMargin, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-calculator fa-3x mb-3"></i>
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

    <!-- Valuation Summary by Category -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Valuation Summary by Category</h5>
                </div>
                <div class="card-body">
                    @php
                        $categorySummary = $items->groupBy('category')->map(function($categoryItems) {
                            return [
                                'count' => $categoryItems->count(),
                                'total_stock' => $categoryItems->sum('current_stock'),
                                'total_value' => $categoryItems->sum('total_value'),
                                'avg_value' => $categoryItems->avg('total_value')
                            ];
                        });
                    @endphp
                    
                    @if($categorySummary->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Items</th>
                                        <th>Total Stock</th>
                                        <th>Total Value</th>
                                        <th>Avg Value per Item</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categorySummary as $category => $data)
                                    <tr>
                                        <td><strong>{{ $category }}</strong></td>
                                        <td>{{ $data['count'] }}</td>
                                        <td>{{ number_format($data['total_stock']) }}</td>
                                        <td class="text-right">
                                            <strong>${{ number_format($data['total_value'], 2) }}</strong>
                                        </td>
                                        <td class="text-right">${{ number_format($data['avg_value'], 2) }}</td>
                                        <td class="text-right">
                                            {{ number_format(($data['total_value'] / $totalValue) * 100, 1) }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>No category data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form on method change
document.getElementById('method').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
