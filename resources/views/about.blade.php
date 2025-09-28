@extends('layouts.app')

@section('title', 'About')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-info-circle me-2"></i>About GemShop</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center py-5">
            <i class="fas fa-gem fa-3x text-warning mb-3"></i>
            <h3 class="text-primary">GemShop Management System</h3>
            <p class="lead">Version 1.0.0</p>
            <p class="text-muted">A comprehensive jewelry and gem shop management system built with Laravel.</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                            <h5>System Management</h5>
                            <p class="small text-muted">User profiles, permissions, and system settings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-gem fa-2x text-warning mb-2"></i>
                            <h5>Inventory Management</h5>
                            <p class="small text-muted">Complete jewelry inventory tracking and management</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                            <h5>Business Analytics</h5>
                            <p class="small text-muted">Sales reports and business intelligence</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection



