@extends('layouts.app')

@section('title', 'Create Alteration Commission')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Alteration Commission</h1>
        <div>
            <a href="{{ route('alteration-commissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Commissions
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
                    <form method="POST" action="{{ route('alteration-commissions.store') }}" id="commissionForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                                       value="Auto-generated" readonly>
                                <small class="form-text text-muted">Commission number will be auto-generated</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sales_assistant_id" class="form-label">Sales Assistant</label>
                                <select class="form-control @error('sales_assistant_id') is-invalid @enderror" id="sales_assistant_id" name="sales_assistant_id">
                                    <option value="">Select Sales Assistant</option>
                                    @foreach($salesAssistants as $salesAssistant)
                                        <option value="{{ $salesAssistant->id }}" {{ old('sales_assistant_id') == $salesAssistant->id ? 'selected' : '' }}>
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
                                        <option value="{{ $craftsman->id }}" {{ old('craftsman_id') == $craftsman->id ? 'selected' : '' }}>
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
                                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
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
                                       value="{{ old('commission_date', date('Y-m-d')) }}" required>
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
                                        <option value="{{ $value }}" {{ old('alteration_type') == $value ? 'selected' : '' }}>
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
                                       value="{{ old('start_date') }}">
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
                                       value="{{ old('commission_amount') }}" required>
                                @error('commission_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select class="form-control @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id" required>
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
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
                                      placeholder="Describe the alteration work to be done...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Additional notes about this commission...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('alteration-commissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Commission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Commission Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Commission Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Status Flow</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pending</h6>
                                    <p class="timeline-text">Commission is created and waiting to be started</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">In Progress</h6>
                                    <p class="timeline-text">Alteration work is being performed</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Completed</h6>
                                    <p class="timeline-text">Alteration work is completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">Alteration Types</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge badge-primary">Resize</span> - Resizing jewelry</li>
                            <li><span class="badge badge-warning">Repair</span> - Repairing damaged items</li>
                            <li><span class="badge badge-info">Polish</span> - Polishing jewelry</li>
                            <li><span class="badge badge-success">Engrave</span> - Engraving work</li>
                            <li><span class="badge badge-secondary">Design Change</span> - Modifying design</li>
                            <li><span class="badge badge-dark">Stone Setting</span> - Setting stones</li>
                            <li><span class="badge badge-light">Cleaning</span> - Cleaning jewelry</li>
                            <li><span class="badge badge-muted">Other</span> - Other alterations</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Commission amounts will be automatically converted to LKR for reporting purposes.
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                        <h6 class="text-primary">Payment Status</h6>
                        <p class="text-muted mb-2">Commissions start as unpaid</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 0%">
                                0% Unpaid
                            </div>
                        </div>
                        <small class="text-muted">Payment tracking will be available after commission creation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}
</style>
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
