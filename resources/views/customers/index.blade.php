@extends('layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-friends me-2"></i>Customer Management
            </h1>
            <p class="text-muted mb-0">Manage your customer database and relationships</p>
        </div>
        <div>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Customer
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->total() }}
                            </div>
                            <div class="text-xs text-muted">
                                All registered customers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->where('is_active', true)->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Currently active customers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Corporate Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Corporate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->where('customer_type', 'corporate')->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Business customers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Individual
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $customers->where('customer_type', 'individual')->count() }}
                            </div>
                            <div class="text-xs text-muted">
                                Personal customers
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('customers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, email, phone, or customer code...">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="corporate" {{ request('type') === 'corporate' ? 'selected' : '' }}>Corporate</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-friends me-2"></i>Customer Database
            </h6>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Customer Code</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Type</th>
                                <th>Financial</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $customer->customer_code }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong class="fw-bold">{{ $customer->full_name }}</strong>
                                        @if($customer->national_id)
                                            <br><small class="text-muted">ID: {{ $customer->national_id }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($customer->email)
                                            <i class="fas fa-envelope me-1"></i>{{ $customer->email }}<br>
                                        @endif
                                        <i class="fas fa-phone me-1"></i>{{ $customer->phone }}
                                    </div>
                                </td>
                                <td>
                                    @if($customer->address)
                                        <div>
                                            {{ Str::limit($customer->address, 30) }}
                                            @if($customer->city)
                                                <br><small class="text-muted">{{ $customer->city }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($customer->customer_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="fw-bold {{ $customer->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($customer->current_balance, 2) }} LKR
                                        </div>
                                        @if($customer->credit_limit > 0)
                                            <small class="text-muted">
                                                Limit: {{ number_format($customer->credit_limit, 2) }} LKR
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($customer->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                    </div>
                    <div>
                        {{ $customers->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customers found</h5>
                    <p class="text-muted">Start by adding your first customer to the system.</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Customer
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



