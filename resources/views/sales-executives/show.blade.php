@extends('layouts.app')

@section('title', 'Sales Executive Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-graduate me-2"></i>{{ $salesExecutive->full_name }}</h2>
    <div>
        <a href="{{ route('sales-executives.edit', $salesExecutive) }}" class="btn btn-secondary me-2">
            <i class="fas fa-edit me-2"></i>Edit Executive
        </a>
        <a href="{{ route('sales-executives.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Executives
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Executive Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <span class="fs-1">{{ substr($salesExecutive->first_name, 0, 1) }}{{ substr($salesExecutive->last_name, 0, 1) }}</span>
                </div>
                
                <h4 class="mb-1">{{ $salesExecutive->full_name }}</h4>
                <p class="text-muted mb-2">{{ $salesExecutive->executive_code }}</p>
                
                @if($salesExecutive->position)
                    <p class="mb-2">
                        <i class="fas fa-briefcase me-1"></i>
                        {{ $salesExecutive->position }}
                    </p>
                @endif
                
                @if($salesExecutive->department)
                    <p class="mb-3">
                        <i class="fas fa-building me-1"></i>
                        {{ $salesExecutive->department }}
                    </p>
                @endif

                <!-- Performance Rating -->
                <div class="mb-3">
                    @php $performance = $salesExecutive->performance_rating; @endphp
                    <div class="mb-3">
                        <i class="fas fa-{{ $performance['icon'] }} fa-3x text-{{ $performance['color'] }}"></i>
                    </div>
                    <h4 class="text-{{ $performance['color'] }}">{{ $performance['rating'] }}</h4>
                    <p class="text-muted mb-0">Based on total sales performance</p>
                </div>

                <!-- Status Badges -->
                <div class="mb-3">
                    <span class="badge {{ $salesExecutive->employment_status_badge }} mb-1">
                        {{ ucfirst(str_replace('_', ' ', $salesExecutive->employment_status)) }}
                    </span>
                    @if($salesExecutive->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h5 class="text-primary mb-1">{{ $salesExecutive->years_of_service }}</h5>
                            <small class="text-muted">Years of Service</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-info mb-1">{{ $salesExecutive->sales_count + $salesExecutive->invoice_count }}</h5>
                        <small class="text-muted">Total Transactions</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-success mb-1">{{ $salesExecutive->sales_count }}</h5>
                            <small class="text-muted">Sales Orders</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning mb-1">{{ $salesExecutive->invoice_count }}</h5>
                        <small class="text-muted">Invoices</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('sales-orders.create') }}?sales_assistant_id={{ $salesExecutive->id }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create Sales Order
                    </a>
                    <a href="{{ route('invoices.create') }}?sales_assistant_id={{ $salesExecutive->id }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-invoice me-2"></i>Create Invoice
                    </a>
                    <a href="{{ route('sales-executives.edit', $salesExecutive) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="mb-0">
                                <i class="fas fa-envelope me-1 text-muted"></i>
                                <a href="mailto:{{ $salesExecutive->email }}">{{ $salesExecutive->email }}</a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="mb-0">
                                <i class="fas fa-phone me-1 text-muted"></i>
                                <a href="tel:{{ $salesExecutive->phone }}">{{ $salesExecutive->phone }}</a>
                            </p>
                        </div>
                        @if($salesExecutive->date_of_birth)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="mb-0">
                                    <i class="fas fa-birthday-cake me-1 text-muted"></i>
                                    {{ $salesExecutive->date_of_birth->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        @if($salesExecutive->gender)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <p class="mb-0">
                                    <i class="fas fa-user me-1 text-muted"></i>
                                    {{ ucfirst($salesExecutive->gender) }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($salesExecutive->hire_date)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hire Date</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                    {{ $salesExecutive->hire_date->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                        @if($salesExecutive->salary)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Salary</label>
                                <p class="mb-0">
                                    <i class="fas fa-dollar-sign me-1 text-muted"></i>
                                    {{ $salesExecutive->formatted_salary }}
                                </p>
                            </div>
                        @endif
                        @if($salesExecutive->national_id)
                            <div class="mb-3">
                                <label class="form-label fw-bold">National ID</label>
                                <p class="mb-0">
                                    <i class="fas fa-id-card me-1 text-muted"></i>
                                    {{ $salesExecutive->national_id }}
                                </p>
                            </div>
                        @endif
                        @if($salesExecutive->days_in_system)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Days in System</label>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-1 text-muted"></i>
                                    {{ number_format($salesExecutive->days_in_system) }} days
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($salesExecutive->full_address)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                            {{ $salesExecutive->full_address }}
                        </p>
                    </div>
                @endif
                
                @if($salesExecutive->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <p class="mb-0">{{ $salesExecutive->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Performance Analytics
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-primary mb-1">{{ displayAmount($salesExecutive->total_sales) }}</h5>
                            <small class="text-muted">Total Sales</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-success mb-1">{{ displayAmount($salesExecutive->total_invoices) }}</h5>
                            <small class="text-muted">Total Invoices</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-info mb-1">{{ displayAmount($salesExecutive->total_sales + $salesExecutive->total_invoices) }}</h5>
                            <small class="text-muted">Combined Total</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h5 class="text-warning mb-1">{{ displayAmount($salesExecutive->average_sale) }}</h5>
                            <small class="text-muted">Average Sale</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">Sales Orders</span>
                            <strong>{{ $salesExecutive->sales_count }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">Invoices</span>
                            <strong>{{ $salesExecutive->invoice_count }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="text-muted">This Month</span>
                            <strong>{{ displayAmount($salesExecutive->getMonthlySales() + $salesExecutive->getMonthlyInvoices()) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Transactions
                </h6>
            </div>
            <div class="card-body">
                @if($salesExecutive->salesOrders->count() > 0 || $salesExecutive->invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Number</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentTransactions = collect()
                                        ->merge($salesExecutive->salesOrders->map(function($order) {
                                            return (object) [
                                                'type' => 'Sales Order',
                                                'number' => $order->order_number,
                                                'customer' => $order->customer?->full_name ?? 'N/A',
                                                'date' => $order->order_date,
                                                'amount' => $order->total_amount,
                                                'status' => $order->status,
                                                'created_at' => $order->created_at
                                            ];
                                        }))
                                        ->merge($salesExecutive->invoices->map(function($invoice) {
                                            return (object) [
                                                'type' => 'Invoice',
                                                'number' => $invoice->invoice_number,
                                                'customer' => $invoice->customer?->full_name ?? 'N/A',
                                                'date' => $invoice->invoice_date,
                                                'amount' => $invoice->total_amount,
                                                'status' => $invoice->status,
                                                'created_at' => $invoice->created_at
                                            ];
                                        }))
                                        ->sortByDesc('created_at')
                                        ->take(10);
                                @endphp
                                
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type === 'Sales Order' ? 'info' : 'success' }}">
                                                {{ $transaction->type }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $transaction->number }}</strong></td>
                                        <td>{{ $transaction->customer }}</td>
                                        <td>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ displayAmount($transaction->amount) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'delivered' || $transaction->status === 'paid' ? 'success' : ($transaction->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No transactions found for this executive.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection