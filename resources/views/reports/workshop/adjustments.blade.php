@extends('layouts.app')

@section('title', 'Workshop Adjustments Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-balance-scale me-2"></i>Workshop Adjustments Report
            </h1>
            <p class="text-muted mb-0">Detailed report of workshop adjustments and material usage</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'adjustments']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'adjustments']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'adjustments']) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.workshop.adjustments') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustment_type" name="adjustment_type">
                            <option value="">All Types</option>
                            <option value="material_used" {{ request('adjustment_type') === 'material_used' ? 'selected' : '' }}>Material Used</option>
                            <option value="scrap" {{ request('adjustment_type') === 'scrap' ? 'selected' : '' }}>Scrap</option>
                            <option value="defective" {{ request('adjustment_type') === 'defective' ? 'selected' : '' }}>Defective</option>
                            <option value="correction" {{ request('adjustment_type') === 'correction' ? 'selected' : '' }}>Correction</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="workshop_location" class="form-label">Workshop Location</label>
                        <select class="form-select" id="workshop_location" name="workshop_location">
                            <option value="">All Locations</option>
                            @php
                                $locations = \App\Models\WorkshopAdjustment::distinct()->pluck('workshop_location')->filter();
                            @endphp
                            @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('workshop_location') === $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="craftsman_id" class="form-label">Craftsman</label>
                        <select class="form-select" id="craftsman_id" name="craftsman_id">
                            <option value="">All Craftsmen</option>
                            @foreach($craftsmen as $craftsman)
                            <option value="{{ $craftsman->id }}" {{ request('craftsman_id') == $craftsman->id ? 'selected' : '' }}>
                                {{ $craftsman->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="item_id" class="form-label">Item</label>
                        <select class="form-select" id="item_id" name="item_id">
                            <option value="">All Items</option>
                            @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('reports.workshop.adjustments') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Adjustments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $adjustments->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $adjustments->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $adjustments->where('status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $adjustments->where('status', 'rejected')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjustment Types Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Adjustment Types Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $adjustments->where('adjustment_type', 'material_used')->count() }}</h4>
                                <p class="text-muted mb-0">Material Used</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $adjustments->where('adjustment_type', 'scrap')->count() }}</h4>
                                <p class="text-muted mb-0">Scrap</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $adjustments->where('adjustment_type', 'defective')->count() }}</h4>
                                <p class="text-muted mb-0">Defective</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $adjustments->where('adjustment_type', 'correction')->count() }}</h4>
                                <p class="text-muted mb-0">Correction</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Workshop Adjustments
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Item</th>
                            <th>Workshop Location</th>
                            <th>Adjustment Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Adjustment Date</th>
                            <th>Craftsman</th>
                            <th>Approved By</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adjustment)
                        <tr>
                            <td>
                                <strong>{{ $adjustment->reference_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $adjustment->item) }}" class="text-decoration-none">
                                    {{ $adjustment->item->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $adjustment->workshop_location ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $adjustment->adjustment_type === 'material_used' ? 'info' : ($adjustment->adjustment_type === 'scrap' ? 'warning' : ($adjustment->adjustment_type === 'defective' ? 'danger' : 'success')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $adjustment->adjustment_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $adjustment->quantity }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $adjustment->status === 'pending' ? 'warning' : ($adjustment->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($adjustment->status) }}
                                </span>
                            </td>
                            <td>{{ $adjustment->adjustment_date->format('M d, Y') }}</td>
                            <td>{{ $adjustment->craftsman->full_name ?? 'N/A' }}</td>
                            <td>{{ $adjustment->approvedBy->name ?? 'Not approved' }}</td>
                            <td>{{ Str::limit($adjustment->reason, 30) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('workshop-adjustments.show', $adjustment) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('workshop-adjustments.edit', $adjustment) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No workshop adjustments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($adjustments->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $adjustments->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
