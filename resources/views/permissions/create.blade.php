@extends('layouts.app')

@section('title', 'Create Permission')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-shield-alt me-2"></i>New Permission</h4>
        <div class="small text-muted">Add a new permission</div>
    </div>
    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. items.view">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Guard (optional)</label>
                <input name="guard_name" value="{{ old('guard_name', config('auth.defaults.guard', 'web')) }}" class="form-control @error('guard_name') is-invalid @enderror">
                @error('guard_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Create</button>
            </div>
        </form>
    </div>
</div>
@endsection


