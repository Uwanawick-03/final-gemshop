@extends('layouts.app')

@section('title', 'Calculator')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
    <div class="text-center">
        <i class="fas fa-calculator fa-4x text-primary mb-4"></i>
        <h3>Redirecting to Advanced Calculator...</h3>
        <p class="text-muted">Please wait while we redirect you to the comprehensive calculator.</p>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
// Redirect to the new calculator after a short delay
setTimeout(function() {
    window.location.href = '{{ route("calculator") }}';
}, 1500);
</script>
@endsection



