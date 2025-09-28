@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.sales.index') }}">Sales Report</a></li>
                        <li class="breadcrumb-item active">Product Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-boxes me-2"></i>Product Sales Report
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.sales.products') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Item name or code">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-select" id="material" name="material">
                                    <option value="">All Materials</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                                            {{ $material }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('reports.sales.products') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
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
                            <h5 class="mb-0">Product Sales Report ({{ $items->total() }} products)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.sales.export-pdf', ['type' => 'products']) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.sales.export-excel', ['type' => 'products']) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.sales.export-csv', ['type' => 'products']) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Material</th>
                                    <th>Current Stock</th>
                                    <th>Sales Count</th>
                                    <th>Total Revenue</th>
                                    <th>Avg Sale Price</th>
                                    <th>Cost Price</th>
                                    <th>Profit Margin</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $item->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->item_code }}</small>
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
                                        <span class="badge bg-primary fs-6">{{ $item->total_sales }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rs {{ number_format($item->total_revenue, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($item->total_sales > 0 ? $item->total_revenue / $item->total_sales : 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($item->cost_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $avgSalePrice = $item->total_sales > 0 ? $item->total_revenue / $item->total_sales : 0;
                                            $profitMargin = $item->cost_price > 0 ? (($avgSalePrice - $item->cost_price) / $avgSalePrice) * 100 : 0;
                                        @endphp
                                        <span class="badge bg-{{ $profitMargin > 0 ? 'success' : ($profitMargin < 0 ? 'danger' : 'secondary') }}">
                                            {{ number_format($profitMargin, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('items.show', $item) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('items.edit', $item) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-boxes fa-3x mb-3"></i>
                                            <p>No products found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($items->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ number_format($items->sum('total_sales')) }}</h4>
                                <p class="text-muted mb-0">Total Sales Count</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">Rs {{ number_format($items->sum('total_revenue'), 2) }}</h4>
                                <p class="text-muted mb-0">Total Revenue</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">Rs {{ number_format($items->avg('total_revenue'), 2) }}</h4>
                                <p class="text-muted mb-0">Average Revenue per Product</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ number_format($items->avg('total_sales'), 1) }}</h4>
                                <p class="text-muted mb-0">Average Sales per Product</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form on filter change
document.getElementById('category').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('material').addEventListener('change', function() {
    this.form.submit();
});

// Auto-submit form on search with debounce
document.getElementById('search').addEventListener('input', function() {
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        this.form.submit();
    }, 500);
});
</script>
@endpush
