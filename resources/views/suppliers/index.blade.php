@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1"><i class="fas fa-truck me-2"></i>Suppliers</h4>
        <div class="small text-muted">Manage supplier profiles</div>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New Supplier</a>
    </div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search code, company, contact, email, phone, city, country">
            </div>
            <div class="col-md-3">
                <select name="payment_terms" class="form-select">
                    <option value="">Any Terms</option>
                    @foreach($terms as $t)
                        <option value="{{ $t }}" @selected(request('payment_terms')===$t)>{{ strtoupper(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Any Status</option>
                    <option value="active" @selected(request('status')==='active')>Active</option>
                    <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Terms</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->supplier_code }}</td>
                            <td><a class="text-decoration-none" href="{{ route('suppliers.show', $supplier) }}">{{ $supplier->company_name }}</a></td>
                            <td>{{ $supplier->contact_person }}</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->city }}</td>
                            <td><span class="badge bg-secondary">{{ strtoupper(str_replace('_',' ',$supplier->payment_terms)) }}</span></td>
                            <td>
                                @if($supplier->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">No suppliers found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $suppliers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection




