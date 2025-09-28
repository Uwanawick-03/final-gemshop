@extends('layouts.app')

@section('title', 'Backup')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-database me-2"></i>Database Backup</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Backup Management</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Backup Status:</strong> Database backup functionality will be implemented soon.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-download fa-2x text-primary mb-2"></i>
                                <h5>Create Backup</h5>
                                <p class="text-muted">Generate a complete database backup</p>
                                <button class="btn btn-primary" disabled>
                                    <i class="fas fa-download me-2"></i>Create Backup
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-upload fa-2x text-success mb-2"></i>
                                <h5>Restore Backup</h5>
                                <p class="text-muted">Restore from a previous backup</p>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-upload me-2"></i>Restore Backup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Backup Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Database:</strong> MySQL<br>
                    <strong>Size:</strong> <span id="dbSize">Calculating...</span><br>
                    <strong>Last Backup:</strong> Not available<br>
                    <strong>Status:</strong> <span class="badge bg-warning">Pending</span>
                </div>
                
                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Regular backups are essential for data protection.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simulate database size calculation
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.getElementById('dbSize').textContent = '2.5 MB';
    }, 1000);
});
</script>
@endsection



