@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-user-shield me-2"></i>New Role</h4>
        <div class="small text-muted">Add a new role and assign permissions</div>
    </div>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. manager">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Guard (optional)</label>
                <input name="guard_name" value="{{ old('guard_name', config('auth.defaults.guard', 'web')) }}" class="form-control @error('guard_name') is-invalid @enderror">
                @error('guard_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">Permissions</label>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                    @foreach($permissions as $permission)
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Create</button>
            </div>
        </form>
    </div>
</div>
@endsection


