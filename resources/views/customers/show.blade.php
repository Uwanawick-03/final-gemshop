@extends('layouts.app')

@section('title', 'Customer Profile - ' . $customer->display_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-user me-2"></i>Customer Profile</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item active">{{ $customer->display_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Profile
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Customer Information Card -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted" width="40%">Customer Code:</td>
                                <td><span class="badge bg-primary">{{ $customer->customer_code }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Full Name:</td>
                                <td>{{ $customer->full_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Email:</td>
                                <td>
                                    @if($customer->email)
                                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Phone:</td>
                                <td>
                                    <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $customer->phone }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Customer Type:</td>
                                <td>
                                    <span class="badge {{ $customer->customer_type === 'individual' ? 'bg-info' : 'bg-warning' }}">
                                        {{ ucfirst($customer->customer_type) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted" width="40%">Date of Birth:</td>
                                <td>
                                    @if($customer->date_of_birth)
                                        {{ $customer->date_of_birth->format('M d, Y') }}
                                        <small class="text-muted">({{ $customer->date_of_birth->age }} years old)</small>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Gender:</td>
                                <td>
                                    @if($customer->gender)
                                        <i class="fas fa-{{ $customer->gender === 'male' ? 'mars text-primary' : 'venus text-pink' }} me-1"></i>
                                        {{ ucfirst($customer->gender) }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">National ID:</td>
                                <td>{{ $customer->national_id ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Member Since:</td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Last Updated:</td>
                                <td>{{ $customer->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
            </div>
            <div class="card-body">
                @if($customer->address || $customer->city || $customer->country)
                    <div class="row">
                        <div class="col-12">
                            <p class="mb-2">
                                <i class="fas fa-home me-2 text-muted"></i>
                                <strong>Full Address:</strong>
                            </p>
                            <p class="ms-4">
                                {{ $customer->full_address ?: 'Address information not complete' }}
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Address:</strong><br>
                            <span class="text-muted">{{ $customer->address ?: 'Not provided' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>City:</strong><br>
                            <span class="text-muted">{{ $customer->city ?: 'Not provided' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Country:</strong><br>
                            <span class="text-muted">{{ $customer->country ?: 'Not provided' }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                        <p>No address information provided</p>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Address
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Financial Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Financial Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-wallet fa-2x text-primary me-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Credit Limit</h6>
                                <h4 class="mb-0">{{ number_format($customer->credit_limit, 2) }} LKR</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-balance-scale fa-2x {{ $customer->current_balance >= 0 ? 'text-success' : 'text-danger' }} me-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Current Balance</h6>
                                <h4 class="mb-0 {{ $customer->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($customer->current_balance, 2) }} LKR
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($customer->credit_limit > 0)
                    <div class="progress mb-3" style="height: 8px;">
                        @php
                            $usage_percentage = $customer->current_balance > 0 ? 
                                min(($customer->current_balance / $customer->credit_limit) * 100, 100) : 0;
                        @endphp
                        <div class="progress-bar {{ $usage_percentage > 80 ? 'bg-danger' : ($usage_percentage > 60 ? 'bg-warning' : 'bg-success') }}" 
                             role="progressbar" 
                             style="width: {{ $usage_percentage }}%"
                             aria-valuenow="{{ $usage_percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">
                        Credit Usage: {{ number_format($usage_percentage, 1) }}% 
                        ({{ number_format($customer->current_balance, 2) }} LKR of {{ number_format($customer->credit_limit, 2) }} LKR)
                    </small>
                @endif
            </div>
        </div>

        <!-- Notes Section -->
        @if($customer->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $customer->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print Profile
                    </button>
                    <a href="mailto:{{ $customer->email }}" class="btn btn-outline-primary" {{ !$customer->email ? 'disabled' : '' }}>
                        <i class="fas fa-envelope me-1"></i> Send Email
                    </a>
                    <a href="tel:{{ $customer->phone }}" class="btn btn-outline-success">
                        <i class="fas fa-phone me-1"></i> Call Customer
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Customer Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="mb-1 text-primary">{{ $customer->invoices()->count() }}</h4>
                            <small class="text-muted">Total Invoices</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="mb-1 text-success">{{ $customer->salesOrders()->count() }}</h4>
                        <small class="text-muted">Sales Orders</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="mb-1 text-warning">{{ $customer->customerReturns()->count() }}</h4>
                            <small class="text-muted">Returns</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-1 text-info">{{ $customer->created_at->diffInDays(now()) }}</h4>
                        <small class="text-muted">Days Active</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Profile Created</h6>
                            <p class="text-muted mb-0 small">{{ $customer->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @if($customer->updated_at != $customer->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Profile Updated</h6>
                            <p class="text-muted mb-0 small">{{ $customer->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

@media print {
    .btn, .card-header .d-flex {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
