@extends('layouts.app')

@section('title', 'Sales Assistant Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-user-tie me-2 text-primary"></i>{{ $salesAssistant->full_name }}
        </h2>
        <div class="text-muted">
            <span class="badge bg-primary me-2">{{ $salesAssistant->assistant_code }}</span>
            {{ $salesAssistant->position ?? 'Sales Assistant' }} • {{ $salesAssistant->department ?? 'No Department' }}
        </div>
    </div>
    <div class="d-flex gap-2">
        @if($salesAssistant->employment_status === 'active' && $salesAssistant->is_active)
        <a href="{{ route('sales-assistants.edit', $salesAssistant) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Profile
        </a>
        @endif
        <a href="{{ route('sales-assistants.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<!-- Performance Overview Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">${{ number_format($salesAssistant->total_sales + $salesAssistant->total_invoices, 0) }}</h3>
                        <p class="mb-0">Total Sales</p>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $salesAssistant->sales_count + $salesAssistant->invoice_count }}</h3>
                        <p class="mb-0">Total Transactions</p>
                    </div>
                    <i class="fas fa-receipt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">${{ number_format($salesAssistant->average_sale, 0) }}</h3>
                        <p class="mb-0">Average Sale</p>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">{{ $salesAssistant->hire_date->diffInYears(now()) }}</h3>
                        <p class="mb-0">Years of Service</p>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Personal & Employment Information -->
    <div class="col-md-8">
    <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Full Name</label>
                        <p class="mb-0 fs-5">{{ $salesAssistant->full_name }}</p>
                    </div>
                    
                    @if($salesAssistant->date_of_birth)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Date of Birth</label>
                        <p class="mb-0">{{ $salesAssistant->date_of_birth->format('M d, Y') }} 
                            <small class="text-muted">({{ $salesAssistant->date_of_birth->age }} years old)</small>
                        </p>
                    </div>
                    @endif
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Email</label>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-1 text-muted"></i>
                            <a href="mailto:{{ $salesAssistant->email }}" class="text-decoration-none">{{ $salesAssistant->email }}</a>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Phone</label>
                        <p class="mb-0">
                            <i class="fas fa-phone me-1 text-muted"></i>
                            <a href="tel:{{ $salesAssistant->phone }}" class="text-decoration-none">{{ $salesAssistant->phone }}</a>
                        </p>
                    </div>
                    
                    @if($salesAssistant->gender)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Gender</label>
                        <p class="mb-0">{{ ucfirst($salesAssistant->gender) }}</p>
                    </div>
                    @endif
                    
                    @if($salesAssistant->national_id)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">National ID</label>
                        <p class="mb-0">{{ $salesAssistant->national_id }}</p>
                    </div>
                    @endif
            </div>
        </div>
    </div>
    
    <!-- Employment Information -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-briefcase me-2 text-success"></i>Employment Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Assistant Code</label>
                        <p class="mb-0">
                            <span class="badge bg-primary fs-6">{{ $salesAssistant->assistant_code }}</span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Hire Date</label>
                        <p class="mb-0">{{ $salesAssistant->hire_date->format('M d, Y') }} 
                            <small class="text-muted">({{ $salesAssistant->hire_date->diffForHumans() }})</small>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Employment Status</label>
                        <p class="mb-0">
                            <span class="badge {{ $salesAssistant->employment_status_badge }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $salesAssistant->employment_status)) }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">System Status</label>
                        <p class="mb-0">
                            @if($salesAssistant->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-secondary fs-6">Inactive</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($salesAssistant->department)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Department</label>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ $salesAssistant->department }}</span>
                        </p>
                    </div>
                    @endif
                    
                    @if($salesAssistant->position)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Position</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $salesAssistant->position }}</span>
                        </p>
                    </div>
                    @endif
                    
                    @if($salesAssistant->salary)
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted">Salary</label>
                        <p class="mb-0">
                            <strong class="text-success fs-4">{{ $salesAssistant->formatted_salary }}</strong>
                            <small class="text-muted">/ year</small>
                        </p>
                    </div>
                    @endif
        </div>
    </div>
</div>

<!-- Address Information -->
@if($salesAssistant->address || $salesAssistant->city || $salesAssistant->country)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>Address Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @if($salesAssistant->address)
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted">Address</label>
                        <p class="mb-0">{{ $salesAssistant->address }}</p>
                    </div>
                    @endif
                    
                    @if($salesAssistant->city)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">City</label>
                        <p class="mb-0">{{ $salesAssistant->city }}</p>
                    </div>
                    @endif
                    
                    @if($salesAssistant->country)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Country</label>
                        <p class="mb-0">{{ $salesAssistant->country }}</p>
                    </div>
                    @endif
        </div>
    </div>
</div>
@endif

<!-- Notes -->
@if($salesAssistant->notes)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-sticky-note me-2 text-info"></i>Notes
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $salesAssistant->notes }}</p>
            </div>
        </div>
        @endif
</div>

    <!-- Performance & Statistics Sidebar -->
    <div class="col-md-4">
        <!-- Performance Rating -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2 text-warning"></i>Performance Rating
                </h5>
                    </div>
            <div class="card-body text-center">
                @php $performance = $salesAssistant->performance_rating; @endphp
                <div class="mb-3">
                    <i class="fas fa-{{ $performance['icon'] }} fa-3x text-{{ $performance['color'] }}"></i>
                </div>
                <h4 class="text-{{ $performance['color'] }}">{{ $performance['rating'] }}</h4>
                <p class="text-muted mb-0">Based on total sales performance</p>
        </div>
    </div>
    
        <!-- Monthly Performance -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>This Month
                </h5>
    </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-primary">${{ number_format($salesAssistant->getMonthlySales(), 0) }}</h5>
                        <small class="text-muted">Sales Orders</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">${{ number_format($salesAssistant->getMonthlyInvoices(), 0) }}</h5>
                        <small class="text-muted">Invoices</small>
                </div>
            </div>
        </div>
    </div>
    
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-info"></i>Quick Stats
                </h5>
    </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Sales Orders</span>
                    <span class="fw-bold">{{ $salesAssistant->sales_count }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Invoices</span>
                    <span class="fw-bold">{{ $salesAssistant->invoice_count }}</span>
                    </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Days in System</span>
                    <span class="fw-bold">{{ $salesAssistant->created_at->diffInDays(now()) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Years of Service</span>
                    <span class="fw-bold">{{ $salesAssistant->hire_date->diffInYears(now()) }}</span>
            </div>
        </div>
    </div>
    
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-success"></i>Quick Actions
                </h5>
    </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($salesAssistant->employment_status === 'active' && $salesAssistant->is_active)
                        <a href="{{ route('sales-assistants.edit', $salesAssistant) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    @endif
                    
                    <a href="{{ route('sales-assistants.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>View All Assistants
                    </a>
                    
                    @if($salesAssistant->email)
                        <a href="mailto:{{ $salesAssistant->email }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                    @endif
                    
                    @if($salesAssistant->phone)
                        <a href="tel:{{ $salesAssistant->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>Call
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Performance Timeline -->
@if($salesAssistant->sales_count > 0 || $salesAssistant->invoice_count > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>Sales Performance Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Recent Sales Orders</h6>
                        @if($salesAssistant->sales_count > 0)
                            @foreach($salesAssistant->salesOrders()->latest()->take(5)->get() as $order)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        <br><small class="text-muted">{{ $order->order_date->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge bg-success">${{ number_format($order->total_amount, 0) }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No sales orders yet</p>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">Recent Invoices</h6>
                        @if($salesAssistant->invoice_count > 0)
                            @foreach($salesAssistant->invoices()->latest()->take(5)->get() as $invoice)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <div>
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                        <br><small class="text-muted">{{ $invoice->invoice_date->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge bg-info">${{ number_format($invoice->total_amount, 0) }}</span>
                    </div>
                            @endforeach
                        @else
                            <p class="text-muted">No invoices yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
