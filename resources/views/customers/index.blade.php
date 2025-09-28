@extends('layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-friends me-2"></i>Customer Management</h2>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Customer
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h4 class="mb-1">{{ $customers->total() }}</h4>
                <small class="text-muted">Total Customers</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                <h4 class="mb-1">{{ $customers->where('is_active', true)->count() }}</h4>
                <small class="text-muted">Active Customers</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-building fa-2x text-info mb-2"></i>
                <h4 class="mb-1">{{ $customers->where('customer_type', 'corporate')->count() }}</h4>
                <small class="text-muted">Corporate</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-user fa-2x text-warning mb-2"></i>
                <h4 class="mb-1">{{ $customers->where('customer_type', 'individual')->count() }}</h4>
                <small class="text-muted">Individual</small>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Search & Filter</h5>
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
<div class="card">
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
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
                                <strong>{{ $customer->customer_code }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $customer->full_name }}</strong>
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
                                       class="btn btn-sm btn-outline-info" 
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
            <div class="d-flex justify-content-center mt-4">
                {{ $customers->withQueryString()->links() }}
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
@endsection



