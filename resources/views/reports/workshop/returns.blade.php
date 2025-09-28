@extends('layouts.app')

@section('title', 'Craftsman Returns Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-arrow-left me-2"></i>Craftsman Returns Report
            </h1>
            <p class="text-muted mb-0">Detailed report of craftsman returns and processing status</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'returns']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'returns']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'returns']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.workshop.returns') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="return_type" class="form-label">Return Type</label>
                        <select class="form-select" id="return_type" name="return_type">
                            <option value="">All Types</option>
                            <option value="defective" {{ request('return_type') === 'defective' ? 'selected' : '' }}>Defective</option>
                            <option value="unused_material" {{ request('return_type') === 'unused_material' ? 'selected' : '' }}>Unused Material</option>
                            <option value="excess" {{ request('return_type') === 'excess' ? 'selected' : '' }}>Excess</option>
                            <option value="quality_issue" {{ request('return_type') === 'quality_issue' ? 'selected' : '' }}>Quality Issue</option>
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
                    <div class="col-md-3 mb-3">
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
                    <div class="col-md-2 mb-3">
                        <label for="quantity_min" class="form-label">Min Quantity</label>
                        <input type="number" class="form-control" id="quantity_min" name="quantity_min" value="{{ request('quantity_min') }}" min="1">
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
                        <a href="{{ route('reports.workshop.returns') }}" class="btn btn-outline-secondary">
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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Returns
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $returns->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-left fa-2x text-gray-300"></i>
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
                                {{ $returns->where('status', 'pending')->count() }}
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
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $returns->where('status', 'completed')->count() }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $returns->where('status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Types Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Return Types Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $returns->where('return_type', 'defective')->count() }}</h4>
                                <p class="text-muted mb-0">Defective Items</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $returns->where('return_type', 'unused_material')->count() }}</h4>
                                <p class="text-muted mb-0">Unused Material</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $returns->where('return_type', 'excess')->count() }}</h4>
                                <p class="text-muted mb-0">Excess Material</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $returns->where('return_type', 'quality_issue')->count() }}</h4>
                                <p class="text-muted mb-0">Quality Issues</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Craftsman Returns
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Return Number</th>
                            <th>Item</th>
                            <th>Craftsman</th>
                            <th>Return Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Return Date</th>
                            <th>Processed By</th>
                            <th>Approved By</th>
                            <th>Reason</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td>
                                <strong>{{ $return->return_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $return->item) }}" class="text-decoration-none">
                                    {{ $return->item->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $return->craftsman->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $return->return_type === 'defective' ? 'danger' : ($return->return_type === 'unused_material' ? 'warning' : ($return->return_type === 'excess' ? 'info' : 'primary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $return->return_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $return->quantity }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'completed' ? 'success' : ($return->status === 'rejected' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td>{{ $return->return_date->format('M d, Y') }}</td>
                            <td>{{ $return->processedBy->name ?? 'Not processed' }}</td>
                            <td>{{ $return->approvedBy->name ?? 'Not approved' }}</td>
                            <td>{{ Str::limit($return->reason, 30) }}</td>
                            <td>{{ Str::limit($return->notes, 20) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('craftsman-returns.show', $return) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('craftsman-returns.edit', $return) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">No craftsman returns found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($returns->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $returns->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
