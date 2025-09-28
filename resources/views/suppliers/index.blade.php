@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck me-2"></i>Supplier Management
            </h1>
            <p class="text-muted mb-0">Manage your supplier database and relationships</p>
        </div>
        <div>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Supplier
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Suppliers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Suppliers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suppliers->total() }}
                            </div>
                            <div class="text-xs text-muted">
                                All registered suppliers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Suppliers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Suppliers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suppliers->where('is_active', true)->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Currently active suppliers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Terms -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Payment Terms
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suppliers->pluck('payment_terms')->unique()->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Different payment terms
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cities -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cities
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suppliers->pluck('city')->unique()->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Different cities
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Search & Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search code, company, contact, email, phone, city, country">
                </div>
                <div class="col-md-3">
                    <select name="payment_terms" class="form-select">
                        <option value="">Any Terms</option>
                        @foreach($terms as $t)
                            <option value="{{ $t }}" @selected(request('payment_terms')===$t)>{{ strtoupper(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Any Status</option>
                        <option value="active" @selected(request('status')==='active')>Active</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-truck me-2"></i>Supplier Database
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Terms</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td><strong class="text-primary">{{ $supplier->supplier_code }}</strong></td>
                                <td><a class="text-decoration-none fw-bold" href="{{ route('suppliers.show', $supplier) }}">{{ $supplier->company_name }}</a></td>
                                <td>{{ $supplier->contact_person }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td><span class="badge bg-secondary">{{ strtoupper(str_replace('_',' ',$supplier->payment_terms)) }}</span></td>
                                <td>
                                    @if($supplier->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <div>No suppliers found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} results
                </div>
                <div>
                    {{ $suppliers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




