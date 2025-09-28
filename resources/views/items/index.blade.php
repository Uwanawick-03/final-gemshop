@extends('layouts.app')

@section('title', 'Items Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-gem me-2"></i>Items Management
            </h1>
            <p class="text-muted mb-0">Browse and manage your inventory items</p>
        </div>
        <div>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Item
            </a>
        </div>
    </div>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-gem me-2"></i>Items Inventory
                </h6>
            </div>
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
                    <table class="table table-bordered table-hover">
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
                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $row->item_code }}</td>
                                    <td>
                                        <a href="{{ route('items.show', $row) }}" class="text-decoration-none fw-bold">{{ $row->name }}</a>
                                        <div>
                                            <span class="badge bg-{{ $row->stock_status_color }} text-uppercase small">{{ str_replace('_',' ',$row->stock_status) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $row->category }} @if($row->subcategory)<span class="text-muted">/ {{ $row->subcategory }}</span>@endif</td>
                                    <td>{{ $row->material }} @if($row->gemstone)<span class="text-muted">/ {{ $row->gemstone }}</span>@endif</td>
                                    <td class="text-end fw-bold">{{ $row->current_stock }} {{ $row->unit }}</td>
                                    <td class="text-end fw-bold text-primary">{{ displayAmount($row->selling_price) }}</td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('items.show', $row) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('items.edit', $row) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <div>No items found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
                    </div>
                    <div>
                        {{ $items->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($items->count() > 0)
    <div class="row mb-4">
        <!-- In Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                In Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $items->where('stock_status', 'in_stock')->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Items available for sale
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $items->where('stock_status', 'low_stock')->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Items need restocking
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $items->where('stock_status', 'out_of_stock')->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Items completely out of stock
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Items -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $items->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Total inventory items
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Top Performing Items
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $topPerformers = $items->sortByDesc('total_sales')->take(3);
                    @endphp
                    
                    @if($topPerformers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Item</th>
                                        <th class="text-end">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPerformers as $index => $item)
                                        <tr>
                                            <td>
                                                <div class="avatar bg-{{ ['primary', 'success', 'info'][$index] ?? 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px;">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $item->name }}</div>
                                                    <small class="text-muted">{{ $item->category }} • {{ $item->material }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success">{{ displayAmount($item->total_sales) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No performance data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Inventory Summary
                    </h6>
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


