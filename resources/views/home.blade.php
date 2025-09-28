@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-home me-2"></i>Welcome Home
            </h1>
            <p class="text-muted mb-0">Welcome to your gem shop management system</p>
        </div>
        <div>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>User Dashboard
                    </h6>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-gem me-2"></i>Welcome to {{ config('app.name') }}
                            </h4>
                            <p class="lead text-muted">
                                You are successfully logged in! This is your personal dashboard where you can manage all aspects of your gem shop business.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                                    <i class="fas fa-tachometer-alt me-1"></i>Go to Main Dashboard
                                </a>
                                <a href="{{ route('items.index') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-gem me-1"></i>Manage Items
                                </a>
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-success">
                                    <i class="fas fa-user-friends me-1"></i>Manage Customers
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-5x text-primary"></i>
                            </div>
                            <h5 class="text-muted">{{ Auth::user()->name ?? 'Guest User' }}</h5>
                            <p class="text-muted small">Logged in successfully</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('items.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus me-1"></i>Add New Item
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('customers.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus me-1"></i>Add Customer
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('purchase-orders.create') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-shopping-cart me-1"></i>Create Purchase Order
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.create') }}" class="btn btn-info btn-block">
                                <i class="fas fa-file-invoice me-1"></i>Create Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
