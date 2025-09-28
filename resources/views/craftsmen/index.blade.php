@extends('layouts.app')

@section('title', 'Craftsmen Profiles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-hammer me-2"></i>Craftsmen Profiles</h2>
    <a href="{{ route('craftsmen.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Craftsman
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('craftsmen.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Name, email, skill...">
            </div>
            <div class="col-md-2">
                <label for="skill" class="form-label">Primary Skill</label>
                <select class="form-select" id="skill" name="skill">
                    <option value="">All Skills</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill }}" {{ request('skill') == $skill ? 'selected' : '' }}>
                            {{ $skill }}
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
                    <a href="{{ route('craftsmen.index') }}" class="btn btn-outline-secondary">
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
                        <h4 class="card-title">{{ $craftsmen->total() }}</h4>
                        <p class="card-text">Total Craftsmen</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hammer fa-2x"></i>
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
                        <h4 class="card-title">{{ $craftsmen->where('employment_status', 'active')->where('is_active', true)->count() }}</h4>
                        <p class="card-text">Active Craftsmen</p>
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
                        <h4 class="card-title">{{ $skills->count() }}</h4>
                        <p class="card-text">Unique Skills</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tools fa-2x"></i>
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
                        <h4 class="card-title">{{ $craftsmen->where('employment_status', 'on_leave')->count() }}</h4>
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
        @if($craftsmen->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Craftsman</th>
                            <th>Contact</th>
                            <th>Primary Skill</th>
                            <th>Performance</th>
                            <th>Rates</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($craftsmen as $craftsman)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ substr($craftsman->first_name, 0, 1) }}{{ substr($craftsman->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $craftsman->full_name }}</div>
                                            <small class="text-muted">{{ $craftsman->craftsman_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($craftsman->email)
                                            <div><i class="fas fa-envelope me-1 text-muted"></i>{{ $craftsman->email }}</div>
                                        @endif
                                        <div><i class="fas fa-phone me-1 text-muted"></i>{{ $craftsman->phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($craftsman->primary_skill)
                                        <span class="badge bg-info">{{ $craftsman->primary_skill }}</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @php $performance = $craftsman->performance_rating; @endphp
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                        <div>
                                            <div class="small fw-bold text-{{ $performance['color'] }}">{{ $performance['rating'] }}</div>
                                            <div class="small text-muted">{{ $craftsman->total_jobs_completed }} jobs</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        @if($craftsman->hourly_rate)
                                            <div><strong>{{ $craftsman->formatted_hourly_rate }}</strong></div>
                                        @endif
                                        @if($craftsman->commission_rate !== null)
                                            <div class="text-muted">{{ $craftsman->formatted_commission_rate }} commission</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $craftsman->employment_status_badge }} mb-1">
                                            {{ ucfirst(str_replace('_', ' ', $craftsman->employment_status)) }}
                                        </span>
                                        @if($craftsman->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('craftsmen.show', $craftsman) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('craftsmen.edit', $craftsman) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('craftsmen.destroy', $craftsman) }}" method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this craftsman?')">
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
                {{ $craftsmen->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-hammer fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Craftsmen Found</h5>
                <p class="text-muted">Get started by adding your first craftsman.</p>
                <a href="{{ route('craftsmen.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Craftsman
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Performance Overview -->
@if($craftsmen->count() > 0)
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
                        $topPerformers = $craftsmen->sortByDesc('total_jobs_completed')->take(3);
                    @endphp
                    
                    @foreach($topPerformers as $index => $craftsman)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }} me-2">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="fw-bold">{{ $craftsman->full_name }}</div>
                                    <small class="text-muted">{{ $craftsman->primary_skill }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $craftsman->total_jobs_completed }} jobs</div>
                                <small class="text-muted">{{ displayAmount($craftsman->total_commissions_earned) }} earned</small>
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
                        $totalJobs = $craftsmen->sum('total_jobs_completed');
                        $averageJobs = $craftsmen->count() > 0 ? $totalJobs / $craftsmen->count() : 0;
                        $masterCraftsmen = $craftsmen->filter(function($craftsman) {
                            $performance = $craftsman->performance_rating;
                            return $performance['rating'] === 'Master Craftsman';
                        })->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-primary mb-1">{{ $totalJobs }}</h5>
                            <small class="text-muted">Total Jobs</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1">{{ round($averageJobs, 1) }}</h5>
                            <small class="text-muted">Average Jobs</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-info mb-1">{{ $masterCraftsmen }}</h5>
                            <small class="text-muted">Master Craftsmen</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection