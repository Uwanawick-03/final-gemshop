@extends('layouts.app')

@section('title', 'Tour Guide Profiles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-map-marked-alt me-2"></i>Tour Guide Profiles</h2>
    <a href="{{ route('tour-guides.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Tour Guide
    </a>
</div>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tour-guides.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Name, email, city...">
            </div>
            <div class="col-md-2">
                <label for="language" class="form-label">Language</label>
                <select class="form-select" id="language" name="language">
                    <option value="">All Languages</option>
                    @foreach($languages as $lang)
                        <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="area" class="form-label">Service Area</label>
                <select class="form-select" id="area" name="area">
                    <option value="">All Areas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                            {{ $area }}
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
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
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
                        <h4 class="card-title">{{ $tourGuides->total() }}</h4>
                        <p class="card-text">Total Guides</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-map-marked-alt fa-2x"></i>
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
                        <h4 class="card-title">{{ $tourGuides->where('employment_status', 'active')->where('is_active', true)->count() }}</h4>
                        <p class="card-text">Active Guides</p>
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
                        <h4 class="card-title">{{ $languages->count() }}</h4>
                        <p class="card-text">Languages</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-language fa-2x"></i>
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
                        <h4 class="card-title">{{ $tourGuides->where('employment_status', 'on_leave')->count() }}</h4>
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
        @if($tourGuides->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Guide</th>
                            <th>Contact</th>
                            <th>Languages</th>
                            <th>Service Areas</th>
                            <th>Performance</th>
                            <th>License Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tourGuides as $guide)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ substr($guide->first_name, 0, 1) }}{{ substr($guide->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $guide->full_name }}</div>
                                            <small class="text-muted">{{ $guide->guide_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($guide->email)
                                            <div><i class="fas fa-envelope me-1 text-muted"></i>{{ $guide->email }}</div>
                                        @endif
                                        <div><i class="fas fa-phone me-1 text-muted"></i>{{ $guide->phone }}</div>
                                        @if($guide->city)
                                            <div><i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $guide->city }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($guide->languages_list)
                                        <div class="small">
                                            @foreach(explode(', ', $guide->languages_list) as $lang)
                                                <span class="badge bg-info me-1 mb-1">{{ trim($lang) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($guide->service_areas_list)
                                        <div class="small">
                                            @foreach(explode(', ', $guide->service_areas_list) as $area)
                                                <span class="badge bg-secondary me-1 mb-1">{{ trim($area) }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @php $performance = $guide->performance_rating; @endphp
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-{{ $performance['icon'] }} text-{{ $performance['color'] }} me-2"></i>
                                        <div>
                                            <div class="small fw-bold text-{{ $performance['color'] }}">{{ $performance['rating'] }}</div>
                                            <div class="small text-muted">{{ $guide->total_tours_conducted }} tours</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $guide->license_status_badge }}">
                                        {{ $guide->license_status }}
                                    </span>
                                    @if($guide->license_expiry)
                                        <div class="small text-muted">{{ $guide->license_expiry->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $guide->employment_status_badge }} mb-1">
                                            {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
                                        </span>
                                        @if($guide->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tour-guides.show', $guide) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tour-guides.edit', $guide) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('tour-guides.destroy', $guide) }}" method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this tour guide?')">
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
                {{ $tourGuides->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Tour Guides Found</h5>
                <p class="text-muted">Get started by adding your first tour guide.</p>
                <a href="{{ route('tour-guides.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Tour Guide
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Performance Overview -->
@if($tourGuides->count() > 0)
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
                        $topPerformers = $tourGuides->sortByDesc('total_tours_conducted')->take(3);
                    @endphp
                    
                    @foreach($topPerformers as $index => $guide)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }} me-2">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <div class="fw-bold">{{ $guide->full_name }}</div>
                                    <small class="text-muted">{{ $guide->languages_list ? explode(', ', $guide->languages_list)[0] : 'Guide' }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $guide->total_tours_conducted }} tours</div>
                                <small class="text-muted">{{ displayAmount($guide->total_earnings) }} earned</small>
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
                        $totalTours = $tourGuides->sum('total_tours_conducted');
                        $averageTours = $tourGuides->count() > 0 ? $totalTours / $tourGuides->count() : 0;
                        $masterGuides = $tourGuides->filter(function($guide) {
                            $performance = $guide->performance_rating;
                            return $performance['rating'] === 'Master Guide';
                        })->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-primary mb-1">{{ $totalTours }}</h5>
                            <small class="text-muted">Total Tours</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1">{{ round($averageTours, 1) }}</h5>
                            <small class="text-muted">Average Tours</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-info mb-1">{{ $masterGuides }}</h5>
                            <small class="text-muted">Master Guides</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection