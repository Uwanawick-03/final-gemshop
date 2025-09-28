@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">
                    <i class="fas fa-gem text-warning me-2"></i>
                    Items
                </h4>
                <div class="small text-muted">Browse and manage items</div>
            </div>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Item
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('items.index') }}" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by code, name, category, material">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected(request('category')===$category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="material" class="form-select">
                            <option value="">All Materials</option>
                            @foreach($materials as $material)
                                <option value="{{ $material }}" @selected(request('material')===$material)>{{ $material }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Material</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Price</th>
                                <th>Performance</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $row)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $row->image_url }}" alt="{{ $row->name }}" 
                                                 class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $row->item_code }}</td>
                                    <td>
                                        <a href="{{ route('items.show', $row) }}" class="text-decoration-none">{{ $row->name }}</a>
                                        <div>
                                            <span class="badge bg-{{ $row->stock_status_color }} text-uppercase small">{{ str_replace('_',' ',$row->stock_status) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $row->category }} @if($row->subcategory)<span class="text-muted">/ {{ $row->subcategory }}</span>@endif</td>
                                    <td>{{ $row->material }} @if($row->gemstone)<span class="text-muted">/ {{ $row->gemstone }}</span>@endif</td>
                                    <td class="text-end">{{ $row->current_stock }} {{ $row->unit }}</td>
                                    <td class="text-end">{{ displayAmount($row->selling_price) }}</td>
                                    <td>
                                        @php $performance = $row->performance_rating; @endphp
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                            <div>
                                                <div class="small fw-bold text-{{ $performance['color'] }}">{{ $performance['rating'] }}</div>
                                                <div class="small text-muted">{{ displayAmount($row->total_sales) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('items.show', $row) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('items.edit', $row) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($items->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $items->where('stock_status', 'in_stock')->count() }}</h4>
                            <p class="mb-0">In Stock</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $items->where('stock_status', 'low_stock')->count() }}</h4>
                            <p class="mb-0">Low Stock</p>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $items->where('stock_status', 'out_of_stock')->count() }}</h4>
                            <p class="mb-0">Out of Stock</p>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $items->count() }}</h4>
                            <p class="mb-0">Total Items</p>
                        </div>
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Top Performing Items</h5>
                </div>
                <div class="card-body">
                    @php
                        $topPerformers = $items->sortByDesc('total_sales')->take(3);
                    @endphp
                    
                    @if($topPerformers->count() > 0)
                        @foreach($topPerformers as $index => $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-{{ ['primary', 'success', 'info'][$index] ?? 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">{{ $item->category }} • {{ $item->material }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <strong class="text-success">{{ displayAmount($item->total_sales) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No performance data available</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Inventory Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalStockValue = $items->sum('stock_value');
                        $totalItems = $items->count();
                        $averageStockValue = $totalItems > 0 ? $totalStockValue / $totalItems : 0;
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ displayAmount($totalStockValue) }}</h4>
                                <small class="text-muted">Total Stock Value</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="mb-1 text-success">{{ displayAmount($averageStockValue) }}</h4>
                            <small class="text-muted">Average per Item</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning">{{ $items->where('is_active', true)->count() }}</h4>
                                <small class="text-muted">Active Items</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1 text-info">{{ $items->sum('current_stock') }}</h4>
                            <small class="text-muted">Total Stock</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection


