@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.inventory.index') }}">Inventory Report</a></li>
                        <li class="breadcrumb-item active">Stock Movements</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-exchange-alt me-2"></i>Stock Movements Report
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
                    <form method="GET" action="{{ route('reports.inventory.movements') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="item_id" class="form-label">Item</label>
                                <select class="form-select" id="item_id" name="item_id">
                                    <option value="">All Items</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" {{ $itemId == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="movement_type" class="form-label">Movement Type</label>
                                <select class="form-select" id="movement_type" name="movement_type">
                                    <option value="">All Types</option>
                                    <option value="purchase" {{ $movementType == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    <option value="sale" {{ $movementType == 'sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="adjustment" {{ $movementType == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                    <option value="transfer" {{ $movementType == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="return" {{ $movementType == 'return' ? 'selected' : '' }}>Return</option>
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
                                <a href="{{ route('reports.inventory.movements') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Stock Movements</h5>
                            <small class="text-muted">From {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.inventory.export-pdf', array_merge(request()->query(), ['type' => 'movements'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.inventory.export-excel', array_merge(request()->query(), ['type' => 'movements'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.inventory.export-csv', array_merge(request()->query(), ['type' => 'movements'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($movements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Item</th>
                                        <th>Type</th>
                                        <th>Reference</th>
                                        <th>Quantity</th>
                                        <th>Balance</th>
                                        <th>Location</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movements as $movement)
                                    <tr>
                                        <td>{{ $movement->date->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $movement->item }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $movement->type == 'Purchase' ? 'success' : ($movement->type == 'Sale' ? 'danger' : 'info') }}">
                                                {{ $movement->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $movement->reference }}</strong>
                                        </td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ $movement->quantity > 0 ? 'success' : 'danger' }}">
                                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <strong>{{ $movement->balance ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $movement->location ?? 'N/A' }}</td>
                                        <td>{{ $movement->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                                <h5>No stock movements found</h5>
                                <p>No stock movements were found for the selected period and criteria.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @if($movements->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Movement Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $movements->count() }}</h4>
                                <p class="text-muted mb-0">Total Movements</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $movements->where('quantity', '>', 0)->count() }}</h4>
                                <p class="text-muted mb-0">Inbound Movements</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $movements->where('quantity', '<', 0)->count() }}</h4>
                                <p class="text-muted mb-0">Outbound Movements</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $movements->sum('quantity') }}</h4>
                                <p class="text-muted mb-0">Net Movement</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Set default date range to last 30 days
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (!startDateInput.value) {
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
        
        startDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
        endDateInput.value = today.toISOString().split('T')[0];
    }
});

// Auto-submit form on filter change
document.getElementById('start_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('end_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('item_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('movement_type').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
