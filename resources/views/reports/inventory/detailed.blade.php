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
                        <li class="breadcrumb-item active">Detailed Report</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-list me-2"></i>Detailed Inventory Report
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
                    <form method="GET" action="{{ route('reports.inventory.detailed') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Item name, code, or description">
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
                            <div class="col-md-2 mb-3">
                                <label for="stock_status" class="form-label">Stock Status</label>
                                <select class="form-select" id="stock_status" name="stock_status">
                                    <option value="">All Status</option>
                                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="overstock" {{ request('stock_status') == 'overstock' ? 'selected' : '' }}>Overstock</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="supplier_id" class="form-label">Supplier</label>
                                <select class="form-select" id="supplier_id" name="supplier_id">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a href="{{ route('reports.inventory.detailed') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
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
                            <h5 class="mb-0">Inventory Items ({{ $items->total() }} total)</h5>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.inventory.export-pdf', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.inventory.export-excel', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.inventory.export-csv', array_merge(request()->query(), ['type' => 'detailed'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
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
                                    <th>Min Stock</th>
                                    <th>Max Stock</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
                                    <td>{{ number_format($item->minimum_stock) }}</td>
                                    <td>{{ number_format($item->maximum_stock) }}</td>
                                    <td class="text-right">${{ number_format($item->cost_price, 2) }}</td>
                                    <td class="text-right">${{ number_format($item->selling_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->stock_status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
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
                                    <td colspan="11" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-boxes fa-3x mb-3"></i>
                                            <p>No items found matching your criteria.</p>
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
                                <h4 class="text-primary">{{ number_format($items->sum('current_stock')) }}</h4>
                                <p class="text-muted mb-0">Total Stock Quantity</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">${{ number_format($items->sum(function($item) { return $item->current_stock * $item->cost_price; }), 2) }}</h4>
                                <p class="text-muted mb-0">Total Cost Value</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">${{ number_format($items->sum(function($item) { return $item->current_stock * $item->selling_price; }), 2) }}</h4>
                                <p class="text-muted mb-0">Total Selling Value</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $items->where('current_stock', '<=', 0)->count() }}</h4>
                                <p class="text-muted mb-0">Out of Stock Items</p>
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

document.getElementById('stock_status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('supplier_id').addEventListener('change', function() {
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
