@extends('layouts.app')

@section('title', 'MTC Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-certificate me-2"></i>MTC Details
            </h1>
            <p class="text-muted mb-0">{{ $mtc->mtc_number }}</p>
        </div>
        <div>
            <a href="{{ route('mtcs.edit', $mtc) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('mtcs.export-pdf', $mtc) }}" class="btn btn-info me-2">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
            <a href="{{ route('mtcs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to MTCs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">MTC Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>MTC Number:</strong></td>
                                    <td>{{ $mtc->mtc_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Item:</strong></td>
                                    <td>{{ $mtc->item->name }} ({{ $mtc->item->item_code }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ $mtc->customer->full_name }} ({{ $mtc->customer->customer_code }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Sales Assistant:</strong></td>
                                    <td>{{ $mtc->salesAssistant->full_name }} ({{ $mtc->salesAssistant->employee_code }})</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Issue Date:</strong></td>
                                    <td>{{ $mtc->issue_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Expiry Date:</strong></td>
                                    <td>{{ $mtc->expiry_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Purchase Price:</strong></td>
                                    <td>${{ number_format($mtc->purchase_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Selling Price:</strong></td>
                                    <td>${{ number_format($mtc->selling_price, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($mtc->notes)
                    <div class="row">
                        <div class="col-md-12">
                            <h6><strong>Notes:</strong></h6>
                            <p>{{ $mtc->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status & Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Current Status:</strong></label>
                        <span class="badge bg-{{ $mtc->status_badge }} fs-6">
                            {{ ucfirst($mtc->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Days Until Expiry:</strong></label>
                        @if($mtc->days_until_expiry !== null)
                            @if($mtc->days_until_expiry < 0)
                                <span class="badge bg-danger">Expired {{ abs($mtc->days_until_expiry) }} days ago</span>
                            @elseif($mtc->days_until_expiry <= 30)
                                <span class="badge bg-warning">{{ $mtc->days_until_expiry }} days</span>
                            @else
                                <span class="badge bg-success">{{ $mtc->days_until_expiry }} days</span>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Profit Margin:</strong></label>
                        @php
                            $margin = $mtc->selling_price - $mtc->purchase_price;
                            $marginPercent = $mtc->purchase_price > 0 ? ($margin / $mtc->purchase_price) * 100 : 0;
                        @endphp
                        <div>
                            <strong>${{ number_format($margin, 2) }}</strong>
                            <small class="text-muted">({{ number_format($marginPercent, 1) }}%)</small>
                        </div>
                    </div>

                    <hr>

                    <h6><strong>Quick Actions:</strong></h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('used')">
                            <i class="fas fa-check me-1"></i>Mark as Used
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="updateStatus('expired')">
                            <i class="fas fa-clock me-1"></i>Mark as Expired
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus('cancelled')">
                            <i class="fas fa-times me-1"></i>Cancel MTC
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm(`Are you sure you want to mark this MTC as ${status}?`)) {
        fetch(`{{ route('mtcs.update-status', $mtc) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        });
    }
}
</script>
@endsection
