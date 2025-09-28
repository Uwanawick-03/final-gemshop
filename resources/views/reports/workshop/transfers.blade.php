@extends('layouts.app')

@section('title', 'Finished Good Transfers Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-check-circle me-2"></i>Finished Good Transfers Report
            </h1>
            <p class="text-muted mb-0">Detailed report of finished good transfers and quality control</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('reports.workshop.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="{{ route('reports.workshop.export-pdf', ['type' => 'transfers']) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.workshop.export-excel', ['type' => 'transfers']) }}" class="btn btn-outline-success">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
                <a href="{{ route('reports.workshop.export-csv', ['type' => 'transfers']) }}" class="btn btn-outline-info">
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
            <form method="GET" action="{{ route('reports.workshop.transfers') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="quality_check" {{ request('status') === 'quality_check' ? 'selected' : '' }}>Quality Check</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="quality_check_passed" class="form-label">Quality Check</label>
                        <select class="form-select" id="quality_check_passed" name="quality_check_passed">
                            <option value="">All</option>
                            <option value="1" {{ request('quality_check_passed') === '1' ? 'selected' : '' }}>Passed</option>
                            <option value="0" {{ request('quality_check_passed') === '0' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="from_workshop" class="form-label">From Workshop</label>
                        <select class="form-select" id="from_workshop" name="from_workshop">
                            <option value="">All Workshops</option>
                            @php
                                $workshops = \App\Models\FinishedGoodTransfer::distinct()->pluck('from_workshop')->filter();
                            @endphp
                            @foreach($workshops as $workshop)
                            <option value="{{ $workshop }}" {{ request('from_workshop') === $workshop ? 'selected' : '' }}>
                                {{ $workshop }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="to_location" class="form-label">To Location</label>
                        <select class="form-select" id="to_location" name="to_location">
                            <option value="">All Locations</option>
                            @php
                                $locations = \App\Models\FinishedGoodTransfer::distinct()->pluck('to_location')->filter();
                            @endphp
                            @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('to_location') === $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
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
                        <a href="{{ route('reports.workshop.transfers') }}" class="btn btn-outline-secondary">
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Transfers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transfers->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                {{ $transfers->where('status', 'completed')->count() }}
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Quality Check
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transfers->where('status', 'quality_check')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
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
                                Quality Failed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transfers->where('quality_check_passed', false)->count() }}
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

    <!-- Quality Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-award me-2"></i>Quality Metrics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @php
                                    $totalTransfers = $transfers->count();
                                    $passedQuality = $transfers->where('quality_check_passed', true)->count();
                                    $qualityRate = $totalTransfers > 0 ? ($passedQuality / $totalTransfers) * 100 : 0;
                                @endphp
                                <h2 class="text-{{ $qualityRate >= 90 ? 'success' : ($qualityRate >= 75 ? 'warning' : 'danger') }}">
                                    {{ number_format($qualityRate, 1) }}%
                                </h2>
                                <p class="text-muted mb-0">Quality Pass Rate</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h2 class="text-success">{{ $passedQuality }}</h2>
                                <p class="text-muted mb-0">Quality Checks Passed</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h2 class="text-danger">{{ $totalTransfers - $passedQuality }}</h2>
                                <p class="text-muted mb-0">Quality Checks Failed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Finished Good Transfers
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Item</th>
                            <th>From Workshop</th>
                            <th>To Location</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Transfer Date</th>
                            <th>Craftsman</th>
                            <th>Transferred By</th>
                            <th>Received By</th>
                            <th>Quality Check</th>
                            <th>Quality Check By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td>
                                <strong>{{ $transfer->reference_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $transfer->item) }}" class="text-decoration-none">
                                    {{ $transfer->item->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $transfer->from_workshop ?? 'N/A' }}</td>
                            <td>{{ $transfer->to_location ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $transfer->quantity }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $transfer->status === 'pending' ? 'warning' : ($transfer->status === 'completed' ? 'success' : ($transfer->status === 'rejected' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td>{{ $transfer->transfer_date->format('M d, Y') }}</td>
                            <td>{{ $transfer->craftsman->full_name ?? 'N/A' }}</td>
                            <td>{{ $transfer->transferredBy->name ?? 'N/A' }}</td>
                            <td>{{ $transfer->receivedBy->name ?? 'Not received' }}</td>
                            <td>
                                @if($transfer->quality_check_passed !== null)
                                    <span class="badge bg-{{ $transfer->quality_check_passed ? 'success' : 'danger' }}">
                                        {{ $transfer->quality_check_passed ? 'Passed' : 'Failed' }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Not checked</span>
                                @endif
                            </td>
                            <td>{{ $transfer->qualityCheckBy->name ?? 'Not checked' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('finished-good-transfers.show', $transfer) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('finished-good-transfers.edit', $transfer) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted">No finished good transfers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($transfers->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $transfers->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
