@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.sales.index') }}">Sales Report</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-chart-line me-2"></i>Sales Analytics
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Analytics Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.sales.analytics') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="period" class="form-label">Period</label>
                                <select class="form-select" id="period" name="period">
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-chart-line me-1"></i>Analyze
                                    </button>
                                    <a href="{{ route('reports.sales.analytics') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Invoices</p>
                            <h4 class="mb-0">{{ number_format($analytics['summary']['total_invoices']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary text-primary font-size-18">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Sales</p>
                            <h4 class="mb-0">Rs {{ number_format($analytics['summary']['total_sales'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success text-success font-size-18">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Average Sale</p>
                            <h4 class="mb-0">Rs {{ number_format($analytics['summary']['average_sale'], 2) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info text-info font-size-18">
                                    <i class="fas fa-chart-bar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Period</p>
                            <h4 class="mb-0 text-capitalize">{{ $analytics['summary']['period'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning text-warning font-size-18">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Sales Analytics</h5>
                            <small class="text-muted">From {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('reports.sales.export-pdf', array_merge(request()->query(), ['type' => 'analytics'])) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('reports.sales.export-excel', array_merge(request()->query(), ['type' => 'analytics'])) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                            <a href="{{ route('reports.sales.export-csv', array_merge(request()->query(), ['type' => 'analytics'])) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-file-csv me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Analytics Data</h5>
                </div>
                <div class="card-body">
                    @if($analytics['data']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Period</th>
                                        <th>Invoices Count</th>
                                        <th>Total Sales</th>
                                        <th>Average Sale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['data'] as $data)
                                    <tr>
                                        <td>
                                            <strong>
                                                @if($period == 'daily')
                                                    {{ \Carbon\Carbon::parse($data->period)->format('M d, Y') }}
                                                @elseif($period == 'weekly')
                                                    Week {{ \Carbon\Carbon::parse($data->period)->format('W, Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($data->period . '-01')->format('M Y') }}
                                                @endif
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $data->invoices_count }}</span>
                                        </td>
                                        <td>
                                            <strong>Rs {{ number_format($data->total_sales, 2) }}</strong>
                                        </td>
                                        <td>
                                            Rs {{ number_format($data->average_sale, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <h5>No analytics data found</h5>
                                <p>No sales data was found for the selected period and criteria.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Set default date range to last 30 days
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (!startDateInput.value) {
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
        
        startDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
        endDateInput.value = today.toISOString().split('T')[0];
    }
});

// Auto-submit form on date change
document.getElementById('start_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('end_date').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('period').addEventListener('change', function() {
    this.form.submit();
});

// Sales Chart
@if($analytics['data']->count() > 0)
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            @foreach($analytics['data'] as $data)
                @if($period == 'daily')
                    '{{ \Carbon\Carbon::parse($data->period)->format("M d") }}',
                @elseif($period == 'weekly')
                    'Week {{ \Carbon\Carbon::parse($data->period)->format("W") }}',
                @else
                    '{{ \Carbon\Carbon::parse($data->period . "-01")->format("M Y") }}',
                @endif
            @endforeach
        ],
        datasets: [{
            label: 'Sales Amount ($)',
            data: [
                @foreach($analytics['data'] as $data)
                    {{ $data->total_sales }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Invoices Count',
            data: [
                @foreach($analytics['data'] as $data)
                    {{ $data->invoices_count }},
                @endforeach
            ],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Sales Amount ($)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Invoices Count'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});
@endif
</script>
@endpush
