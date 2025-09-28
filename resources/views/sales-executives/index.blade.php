@extends('layouts.app')

@section('title', 'Sales Executives')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-graduate me-2"></i>Sales Executives</h2>
    <a href="{{ route('sales-executives.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Sales Executive
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales-executives.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Name, email, department...">
            </div>
            <div class="col-md-2">
                <label for="department" class="form-label">Department</label>
                <select class="form-select" id="department" name="department">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Employment Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="active" class="form-label">Active Status</label>
                <select class="form-select" id="active" name="active">
                    <option value="">All</option>
                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active Only</option>
                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('sales-executives.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $salesExecutives->total() }}</h4>
                        <p class="card-text">Total Executives</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $salesExecutives->where('employment_status', 'active')->where('is_active', true)->count() }}</h4>
                        <p class="card-text">Active Executives</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $departments->count() }}</h4>
                        <p class="card-text">Departments</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $salesExecutives->where('employment_status', 'on_leave')->count() }}</h4>
                        <p class="card-text">On Leave</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($salesExecutives->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Executive</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Performance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesExecutives as $executive)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ substr($executive->first_name, 0, 1) }}{{ substr($executive->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $executive->full_name }}</div>
                                            <small class="text-muted">{{ $executive->executive_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="fas fa-envelope me-1 text-muted"></i>{{ $executive->email }}</div>
                                        <div><i class="fas fa-phone me-1 text-muted"></i>{{ $executive->phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($executive->department)
                                        <span class="badge bg-info">{{ $executive->department }}</span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>{{ $executive->position ?? 'Not specified' }}</td>
                                <td>
                                    @php $performance = $executive->performance_rating; @endphp
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                        <div>
                                            <div class="small fw-bold text-{{ $performance['color'] }}">{{ $performance['rating'] }}</div>
                                            <div class="small text-muted">{{ displayAmount($executive->total_sales + $executive->total_invoices) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $executive->employment_status_badge }} mb-1">
                                            {{ ucfirst(str_replace('_', ' ', $executive->employment_status)) }}
                                        </span>
                                        @if($executive->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('sales-executives.show', $executive) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales-executives.edit', $executive) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales-executives.destroy', $executive) }}" method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this sales executive?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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
                {{ $salesExecutives->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Sales Executives Found</h5>
                <p class="text-muted">Get started by adding your first sales executive.</p>
                <a href="{{ route('sales-executives.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Sales Executive
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Performance Overview -->
@if($salesExecutives->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Top Performers
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $topPerformers = $salesExecutives->sortByDesc(function($executive) {
                            return $executive->total_sales + $executive->total_invoices;
                        })->take(3);
                    @endphp
                    
                    @foreach($topPerformers as $index => $executive)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }} me-2">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="fw-bold">{{ $executive->full_name }}</div>
                                    <small class="text-muted">{{ $executive->department }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ displayAmount($executive->total_sales + $executive->total_invoices) }}</div>
                                <small class="text-muted">{{ $executive->sales_count + $executive->invoice_count }} transactions</small>
                            </div>
                        </div>
                        @if($index < 2)
                            <hr class="my-2">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Performance Summary
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalSales = $salesExecutives->sum(function($executive) {
                            return $executive->total_sales + $executive->total_invoices;
                        });
                        $averageSales = $salesExecutives->count() > 0 ? $totalSales / $salesExecutives->count() : 0;
                        $excellentPerformers = $salesExecutives->filter(function($executive) {
                            $performance = $executive->performance_rating;
                            return in_array($performance['rating'], ['Exceptional', 'Excellent']);
                        })->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-primary mb-1">{{ displayAmount($totalSales) }}</h5>
                            <small class="text-muted">Total Sales</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1">{{ displayAmount($averageSales) }}</h5>
                            <small class="text-muted">Average</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-info mb-1">{{ $excellentPerformers }}</h5>
                            <small class="text-muted">Top Performers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection