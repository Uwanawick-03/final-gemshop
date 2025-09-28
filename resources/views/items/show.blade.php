@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">
                    <i class="fas fa-gem text-warning me-2"></i>
                    {{ $item->name }}
                    <span class="text-muted">({{ $item->item_code }})</span>
                </h4>
                <div class="small text-muted">
                    <a href="{{ route('items.index') }}" class="text-decoration-none">Items</a>
                    <span class="mx-1">/</span>
                    <span>{{ $item->name }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </form>
                <a href="{{ route('items.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="ratio ratio-1x1 rounded border bg-light d-flex align-items-center justify-content-center overflow-hidden">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-{{ $item->stock_status_color }} text-uppercase">{{ str_replace('_', ' ', $item->stock_status) }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">Active</span>
                            @if($item->is_active)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted">Taxable</span>
                            @if($item->is_taxable)
                                <span class="badge bg-info text-dark">Yes ({{ number_format($item->tax_rate, 2) }}%)</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-3">General</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Category</div>
                                <div class="fw-semibold">{{ $item->category }} @if($item->subcategory) <span class="text-muted">/ {{ $item->subcategory }}</span> @endif</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Material</div>
                                <div class="fw-semibold">{{ $item->material }} @if($item->gemstone) <span class="text-muted">/ {{ $item->gemstone }}</span> @endif</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Weight</div>
                                <div class="fw-semibold">{{ $item->weight ? number_format($item->weight, 3).' g' : '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Size</div>
                                <div class="fw-semibold">{{ $item->size ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Purity</div>
                                <div class="fw-semibold">{{ $item->purity ? number_format($item->purity, 2).' K' : '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="small text-muted">Barcode</div>
                                <div class="fw-semibold">{{ $item->barcode ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="small text-muted">Description</div>
                                <div>{{ $item->description ? nl2br(e($item->description)) : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase mb-3">Pricing</h6>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Cost Price</span>
                                    <span class="fw-bold">{{ displayAmount($item->cost_price) }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Selling Price</span>
                                    <span class="fw-bold text-success">{{ displayAmount($item->selling_price) }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Wholesale Price</span>
                                    <span class="fw-bold">{{ $item->wholesale_price ? displayAmount($item->wholesale_price) : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase mb-3">Inventory</h6>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Current Stock</span>
                                    <span class="fw-bold">{{ $item->current_stock }} {{ $item->unit }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Minimum Stock</span>
                                    <span class="fw-bold">{{ $item->minimum_stock }} {{ $item->unit }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Maximum Stock</span>
                                    <span class="fw-bold">{{ $item->maximum_stock ? $item->maximum_stock.' '.$item->unit : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($item->notes)
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase mb-3">Internal Notes</h6>
                            <div class="bg-light rounded p-3">{!! nl2br(e($item->notes)) !!}</div>
                        </div>
                    </div>
                @endif

                <!-- Performance Analytics -->
                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>Performance Analytics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h5 class="text-primary mb-1">{{ displayAmount($item->total_sales) }}</h5>
                                    <small class="text-muted">Total Sales</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h5 class="text-success mb-1">{{ $item->sales_count }}</h5>
                                    <small class="text-muted">Sales Count</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h5 class="text-info mb-1">{{ number_format($item->profit_margin, 1) }}%</h5>
                                    <small class="text-muted">Profit Margin</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    @php $performance = $item->performance_rating; @endphp
                                    <div class="d-flex align-items-center justify-content-center mb-1">
                                        <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                        <h6 class="text-{{ $performance['color'] }} mb-0">{{ $performance['rating'] }}</h6>
                                    </div>
                                    <small class="text-muted">Performance</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted">Quantity Sold</span>
                                    <strong>{{ $item->total_quantity_sold }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted">Average Sale Price</span>
                                    <strong>{{ displayAmount($item->average_sale_price) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                    <span class="text-muted">Stock Value</span>
                                    <strong>{{ displayAmount($item->stock_value) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


