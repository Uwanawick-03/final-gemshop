@extends('layouts.app')

@section('title', 'Edit Alteration Commission')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Alteration Commission</h1>
        <div>
            <a href="{{ route('alteration-commissions.show', $alterationCommission) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Commission
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Commission Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('alteration-commissions.update', $alterationCommission) }}" id="commissionForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $alterationCommission->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="commission_number" class="form-label">Commission Number</label>
                                <input type="text" class="form-control" id="commission_number" 
                                       value="{{ $alterationCommission->commission_number }}" readonly>
                                <small class="form-text text-muted">Commission number cannot be changed</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sales_assistant_id" class="form-label">Sales Assistant</label>
                                <select class="form-control @error('sales_assistant_id') is-invalid @enderror" id="sales_assistant_id" name="sales_assistant_id">
                                    <option value="">Select Sales Assistant</option>
                                    @foreach($salesAssistants as $salesAssistant)
                                        <option value="{{ $salesAssistant->id }}" {{ old('sales_assistant_id', $alterationCommission->sales_assistant_id) == $salesAssistant->id ? 'selected' : '' }}>
                                            {{ $salesAssistant->first_name }} {{ $salesAssistant->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sales_assistant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="craftsman_id" class="form-label">Craftsman</label>
                                <select class="form-control @error('craftsman_id') is-invalid @enderror" id="craftsman_id" name="craftsman_id">
                                    <option value="">Select Craftsman</option>
                                    @foreach($craftsmen as $craftsman)
                                        <option value="{{ $craftsman->id }}" {{ old('craftsman_id', $alterationCommission->craftsman_id) == $craftsman->id ? 'selected' : '' }}>
                                            {{ $craftsman->first_name }} {{ $craftsman->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('craftsman_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_id" class="form-label">Item</label>
                                <select class="form-control @error('item_id') is-invalid @enderror" id="item_id" name="item_id">
                                    <option value="">Select Item (Optional)</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" {{ old('item_id', $alterationCommission->item_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} ({{ $item->item_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="commission_date" class="form-label">Commission Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('commission_date') is-invalid @enderror" 
                                       id="commission_date" name="commission_date" 
                                       value="{{ old('commission_date', $alterationCommission->commission_date->format('Y-m-d')) }}" required>
                                @error('commission_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="alteration_type" class="form-label">Alteration Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('alteration_type') is-invalid @enderror" id="alteration_type" name="alteration_type" required>
                                    <option value="">Select Alteration Type</option>
                                    @foreach($alterationTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('alteration_type', $alterationCommission->alteration_type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('alteration_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', $alterationCommission->start_date?->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="commission_amount" class="form-label">Commission Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" 
                                       class="form-control @error('commission_amount') is-invalid @enderror" 
                                       id="commission_amount" name="commission_amount" 
                                       value="{{ old('commission_amount', $alterationCommission->commission_amount) }}" required>
                                @error('commission_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select class="form-control @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id" required>
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id', $alterationCommission->currency_id) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Describe the alteration work to be done...">{{ old('description', $alterationCommission->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes about this commission...">{{ old('notes', $alterationCommission->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('alteration-commissions.show', $alterationCommission) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Commission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Commission Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Commission Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Status</h6>
                        <span class="badge badge-{{ $alterationCommission->status_color }} badge-lg">
                            {{ $alterationCommission->status_label }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Payment Status</h6>
                        <span class="badge badge-{{ $alterationCommission->payment_status_color }} badge-lg">
                            {{ $alterationCommission->payment_status_label }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Commission Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Number:</td>
                                <td><strong>{{ $alterationCommission->commission_number }}</strong></td>
                            </tr>
                            <tr>
                                <td>Customer:</td>
                                <td><strong>{{ $alterationCommission->customer->first_name }} {{ $alterationCommission->customer->last_name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Type:</td>
                                <td><span class="badge badge-{{ $alterationCommission->alteration_type_color }}">{{ $alterationCommission->alteration_type_label }}</span></td>
                            </tr>
                            <tr>
                                <td>Amount:</td>
                                <td><strong>{{ number_format($alterationCommission->commission_amount, 2) }} {{ $alterationCommission->currency->code }}</strong></td>
                            </tr>
                            <tr>
                                <td>Date:</td>
                                <td>{{ $alterationCommission->commission_date->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    @if($alterationCommission->is_overdue)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Overdue:</strong> This commission is {{ $alterationCommission->days_overdue }} days overdue.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                        <h6 class="text-primary">Payment Progress</h6>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar bg-{{ $alterationCommission->payment_status_color }}" 
                                 role="progressbar" 
                                 style="width: {{ $alterationCommission->progress_percentage }}%"
                                 aria-valuenow="{{ $alterationCommission->progress_percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $alterationCommission->progress_percentage }}%
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h6 class="text-success">{{ number_format($alterationCommission->paid_amount ?? 0, 2) }}</h6>
                                    <small class="text-muted">Paid</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-danger">{{ number_format($alterationCommission->remaining_amount, 2) }}</h6>
                                <small class="text-muted">Remaining</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Restrictions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Restrictions</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Commission number cannot be changed</li>
                            <li>Completed commissions cannot be edited</li>
                            <li>Payment information should be updated separately</li>
                            <li>Status changes should be done from the commission details page</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commissionDateInput = document.getElementById('commission_date');
    const startDateInput = document.getElementById('start_date');

    // Set minimum start date to commission date
    commissionDateInput.addEventListener('change', function() {
        startDateInput.min = this.value;
        if (startDateInput.value && startDateInput.value < this.value) {
            startDateInput.value = this.value;
        }
    });

    // Initialize start date minimum
    if (commissionDateInput.value) {
        startDateInput.min = commissionDateInput.value;
    }
});
</script>
@endpush
