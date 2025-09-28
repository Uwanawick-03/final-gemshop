@extends('layouts.app')

@section('title', 'Sales Assistant Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-user-tie me-2"></i>Sales Assistant Management</h2>
        <p class="text-muted mb-0">Manage your sales assistant team</p>
    </div>
    <a href="{{ route('sales-assistants.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Assistant
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales-assistants.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name, email, phone, or code...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select class="form-select" name="department">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Active</label>
                <select class="form-select" name="active">
                    <option value="">All</option>
                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Sales Assistants Table -->
<div class="card">
    <div class="card-body">
        @if($salesAssistants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Performance</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesAssistants as $assistant)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $assistant->assistant_code }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $assistant->full_name }}</strong>
                                    @if($assistant->national_id)
                                        <br><small class="text-muted">ID: {{ $assistant->national_id }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-envelope me-1 text-muted"></i>{{ $assistant->email }}<br>
                                    <i class="fas fa-phone me-1 text-muted"></i>{{ $assistant->phone }}
                                </div>
                            </td>
                            <td>
                                @if($assistant->department)
                                    <span class="badge bg-info">{{ $assistant->department }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($assistant->position)
                                    <span class="badge bg-secondary">{{ $assistant->position }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $assistant->employment_status_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $assistant->employment_status)) }}
                                </span>
                                @if($assistant->is_active)
                                    <br><small class="text-success">Active</small>
                                @else
                                    <br><small class="text-muted">Inactive</small>
                                @endif
                            </td>
                            <td>
                                @php $performance = $assistant->performance_rating; @endphp
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                    <div>
                                        <div class="small fw-bold text-{{ $performance['color'] }}">{{ $performance['rating'] }}</div>
                                        <div class="small text-muted">Rs {{ number_format($assistant->total_sales + $assistant->total_invoices, 0) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($assistant->salary)
                                    <strong class="text-success">{{ $assistant->formatted_salary }}</strong>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sales-assistants.show', $assistant) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sales-assistants.edit', $assistant) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('sales-assistants.destroy', $assistant) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this sales assistant?')">
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
                {{ $salesAssistants->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No sales assistants found</h5>
                <p class="text-muted">Start by adding your first sales assistant to the system.</p>
                <a href="{{ route('sales-assistants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Assistant
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Statistics Dashboard -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $salesAssistants->where('employment_status', 'active')->count() }}</h4>
                        <p class="mb-0">Active Assistants</p>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $departments->count() }}</h4>
                        <p class="mb-0">Departments</p>
                    </div>
                    <i class="fas fa-building fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $salesAssistants->where('is_active', true)->count() }}</h4>
                        <p class="mb-0">Total Active</p>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $salesAssistants->where('employment_status', 'on_leave')->count() }}</h4>
                        <p class="mb-0">On Leave</p>
                    </div>
                    <i class="fas fa-calendar-times fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Top Performers</h5>
            </div>
            <div class="card-body">
                @php
                    $topPerformers = $salesAssistants->sortByDesc(function($assistant) {
                        return $assistant->total_sales + $assistant->total_invoices;
                    })->take(3);
                @endphp
                
                @if($topPerformers->count() > 0)
                    @foreach($topPerformers as $index => $assistant)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar bg-{{ ['primary', 'success', 'info'][$index] ?? 'secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $assistant->full_name }}</h6>
                                <small class="text-muted">{{ $assistant->department ?? 'No Department' }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <strong class="text-success">Rs {{ number_format($assistant->total_sales + $assistant->total_invoices, 0) }}</strong>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No performance data available</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Performance Summary</h5>
            </div>
            <div class="card-body">
                @php
                    $totalSales = $salesAssistants->sum('total_sales') + $salesAssistants->sum('total_invoices');
                    $averageSales = $salesAssistants->count() > 0 ? $totalSales / $salesAssistants->count() : 0;
                    $excellentPerformers = $salesAssistants->filter(function($assistant) {
                        return ($assistant->total_sales + $assistant->total_invoices) >= 100000;
                    })->count();
                @endphp
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="mb-1 text-primary">Rs {{ number_format($totalSales, 0) }}</h4>
                            <small class="text-muted">Total Sales</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="mb-1 text-success">Rs {{ number_format($averageSales, 0) }}</h4>
                        <small class="text-muted">Average per Assistant</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="mb-1 text-warning">{{ $excellentPerformers }}</h4>
                            <small class="text-muted">Top Performers</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-1 text-info">{{ $salesAssistants->where('employment_status', 'active')->count() }}</h4>
                        <small class="text-muted">Active Team</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
