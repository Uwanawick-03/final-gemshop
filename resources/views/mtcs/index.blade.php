@extends('layouts.app')

@section('title', 'MTC Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-certificate me-2"></i>MTC Management
            </h1>
            <p class="text-muted mb-0">Material Transfer Certificates</p>
        </div>
        <a href="{{ route('mtcs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create MTC
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('mtcs.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search MTCs...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select class="form-select" name="customer_id">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select class="form-select" name="sales_assistant_id">
                            <option value="">All Assistants</option>
                            @foreach($salesAssistants as $assistant)
                            <option value="{{ $assistant->id }}" {{ request('sales_assistant_id') == $assistant->id ? 'selected' : '' }}>
                                {{ $assistant->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('mtcs.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MTCs Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">MTCs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>MTC Number</th>
                            <th>Item</th>
                            <th>Customer</th>
                            <th>Sales Assistant</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mtcs as $mtc)
                        <tr>
                            <td><input type="checkbox" class="mtc-checkbox" value="{{ $mtc->id }}"></td>
                            <td><strong>{{ $mtc->mtc_number }}</strong></td>
                            <td>{{ $mtc->item->name }}</td>
                            <td>{{ $mtc->customer->full_name }}</td>
                            <td>{{ $mtc->salesAssistant->full_name }}</td>
                            <td>{{ $mtc->issue_date->format('M d, Y') }}</td>
                            <td>{{ $mtc->expiry_date->format('M d, Y') }}</td>
                            <td>${{ number_format($mtc->purchase_price, 2) }}</td>
                            <td>${{ number_format($mtc->selling_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $mtc->status_badge }}">
                                    {{ ucfirst($mtc->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('mtcs.show', $mtc) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mtcs.edit', $mtc) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('mtcs.export-pdf', $mtc) }}" class="btn btn-outline-info">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No MTCs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $mtcs->appends(request()->query())->links() }}
</div>
@endsection
