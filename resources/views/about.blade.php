@extends('layouts.app')

@section('title', 'About GemShop')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-info-circle me-2"></i>About GemShop Management System
            </h1>
            <p class="text-muted mb-0">Comprehensive jewelry and gem shop management platform</p>
        </div>
        <div>
            <button class="btn btn-outline-info" onclick="refreshSystemInfo()">
                <i class="fas fa-sync-alt me-1"></i>Refresh Info
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- System Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-gem fa-4x text-warning mb-3"></i>
                    <h2 class="text-primary mb-2">{{ $systemInfo['app_name'] }}</h2>
                    <p class="lead text-muted mb-3">Version {{ $systemInfo['app_version'] }}</p>
                    <p class="text-muted">A comprehensive jewelry and gem shop management system built with Laravel {{ $systemInfo['laravel_version'] }}</p>
                    
                    <!-- System Status Badge -->
                    <div class="mt-3">
                        @if($systemHealth['overall'] === 'good')
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>System Healthy
                            </span>
                        @elseif($systemHealth['overall'] === 'warning')
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-exclamation-triangle me-1"></i>System Warning
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>System Issues
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['items'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gem fa-2x text-gray-300"></i>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['customers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['invoices'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            
    <!-- Feature Modules -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-puzzle-piece me-2"></i>System Modules
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($featureModules as $module)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-left-{{ $module['color'] }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="{{ $module['icon'] }} fa-2x text-{{ $module['color'] }} me-3"></i>
                                        <div>
                                            <h6 class="mb-1">{{ $module['name'] }}</h6>
                                            <small class="text-muted">{{ $module['description'] }}</small>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        @foreach($module['features'] as $feature)
                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
</div>

    <!-- System Information & Health -->
    <div class="row mb-4">
        <!-- System Information -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-server me-2"></i>System Information
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>PHP Version:</strong></td>
                            <td>{{ $systemInfo['php_version'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Laravel Version:</strong></td>
                            <td>{{ $systemInfo['laravel_version'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Environment:</strong></td>
                            <td>
                                <span class="badge bg-{{ $systemInfo['app_env'] === 'production' ? 'success' : 'warning' }}">
                                    {{ ucfirst($systemInfo['app_env']) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Debug Mode:</strong></td>
                            <td>
                                <span class="badge bg-{{ $systemInfo['app_debug'] ? 'danger' : 'success' }}">
                                    {{ $systemInfo['app_debug'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Timezone:</strong></td>
                            <td>{{ $systemInfo['timezone'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Locale:</strong></td>
                            <td>{{ $systemInfo['locale'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Database:</strong></td>
                            <td>{{ $systemInfo['database_driver'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cache:</strong></td>
                            <td>{{ $systemInfo['cache_driver'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat me-2"></i>System Health
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($systemHealth['checks'] as $check => $status)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ ucfirst(str_replace('_', ' ', $check)) }}</strong>
                            <br><small class="text-muted">{{ $status['message'] }}</small>
                        </div>
                        <div>
                            @if($status['status'] === 'good')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Good
                                </span>
                            @elseif($status['status'] === 'warning')
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Warning
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Error
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Database Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-database me-2"></i>Database Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h4 class="text-primary">{{ $databaseInfo['total_tables'] }}</h4>
                            <p class="text-muted mb-0">Database Tables</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-success">{{ number_format($databaseInfo['total_records']) }}</h4>
                            <p class="text-muted mb-0">Total Records</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-info">{{ $databaseInfo['database_name'] }}</h4>
                            <p class="text-muted mb-0">Database Name</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-warning">{{ ucfirst($databaseInfo['database_driver']) }}</h4>
                            <p class="text-muted mb-0">Driver</p>
                        </div>
                    </div>
                    
                    @if(count($databaseInfo['tables']) > 0)
                    <hr>
                    <h6>Table Overview:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Table Name</th>
                                    <th>Records</th>
                                    <th>Size (MB)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($databaseInfo['tables'], 0, 10) as $table)
                                <tr>
                                    <td>{{ $table['name'] }}</td>
                                    <td>{{ number_format($table['count']) }}</td>
                                    <td>{{ number_format($table['size'], 2) }}</td>
                                </tr>
                                @endforeach
                                @if(count($databaseInfo['tables']) > 10)
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        ... and {{ count($databaseInfo['tables']) - 10 }} more tables
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Additional Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-primary">{{ $systemStats['suppliers'] }}</h5>
                            <small class="text-muted">Suppliers</small>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-success">{{ $systemStats['purchase_orders'] }}</h5>
                            <small class="text-muted">Purchase Orders</small>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-info">{{ $systemStats['job_issues'] }}</h5>
                            <small class="text-muted">Job Issues</small>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-warning">{{ $systemStats['mtcs'] }}</h5>
                            <small class="text-muted">MTCs</small>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-danger">{{ $systemStats['tour_guides'] }}</h5>
                            <small class="text-muted">Tour Guides</small>
                        </div>
                        <div class="col-md-2 col-sm-4 col-6 text-center mb-3">
                            <h5 class="text-secondary">{{ $systemStats['craftsmen'] }}</h5>
                            <small class="text-muted">Craftsmen</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshSystemInfo() {
    fetch('{{ route("about.system-info") }}')
        .then(response => response.json())
        .then(data => {
            console.log('System info refreshed:', data);
            location.reload();
        })
        .catch(error => {
            console.error('Error refreshing system info:', error);
            alert('Error refreshing system information');
        });
}
</script>
@endsection
