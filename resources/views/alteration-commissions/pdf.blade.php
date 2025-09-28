<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteration Commission - {{ $alterationCommission->commission_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 18px;
            color: #666;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .company-info h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .company-info p {
            margin: 2px 0;
            color: #666;
        }
        
        .commission-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .commission-info-left, .commission-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-section h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-in-progress { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        
        .payment-status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .payment-status-unpaid { background-color: #f8d7da; color: #721c24; }
        .payment-status-partial { background-color: #fff3cd; color: #856404; }
        .payment-status-paid { background-color: #d4edda; color: #155724; }
        
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .type-resize { background-color: #cce5ff; color: #004085; }
        .type-repair { background-color: #fff3cd; color: #856404; }
        .type-polish { background-color: #d1ecf1; color: #0c5460; }
        .type-engrave { background-color: #d4edda; color: #155724; }
        .type-design-change { background-color: #e2e3e5; color: #383d41; }
        .type-stone-setting { background-color: #343a40; color: #fff; }
        .type-cleaning { background-color: #f8f9fa; color: #6c757d; }
        .type-other { background-color: #6c757d; color: #fff; }
        
        .amount-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .amount-section h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .amount-currency {
            color: #666;
            font-size: 14px;
        }
        
        .payment-progress {
            margin-top: 15px;
        }
        
        .progress-bar {
            background-color: #e9ecef;
            border-radius: 3px;
            height: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .progress-unpaid { background-color: #dc3545; }
        .progress-partial { background-color: #ffc107; }
        .progress-paid { background-color: #28a745; }
        
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }
        
        .description-section {
            margin-top: 30px;
        }
        
        .description-section h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .description-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            min-height: 50px;
        }
        
        .timeline {
            margin-top: 30px;
        }
        
        .timeline h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .timeline-item {
            margin-bottom: 15px;
            padding-left: 20px;
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ddd;
        }
        
        .timeline-item.active::before {
            background-color: #007bff;
        }
        
        .timeline-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 2px;
        }
        
        .timeline-text {
            font-size: 10px;
            color: #666;
            margin: 0;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Alteration Commission</h1>
        <h2>{{ $alterationCommission->commission_number }}</h2>
    </div>

    <div class="company-info">
        <h3>GemShop</h3>
        <p>Alteration Commission Management System</p>
        <p>Generated on: {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="commission-info">
        <div class="commission-info-left">
            <div class="info-section">
                <h4>Commission Information</h4>
                <div class="info-row">
                    <span class="info-label">Commission Number:</span>
                    <span class="info-value">{{ $alterationCommission->commission_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Commission Date:</span>
                    <span class="info-value">{{ $alterationCommission->commission_date->format('M d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge status-{{ $alterationCommission->status }}">
                        {{ $alterationCommission->status_label }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alteration Type:</span>
                    <span class="type-badge type-{{ $alterationCommission->alteration_type }}">
                        {{ $alterationCommission->alteration_type_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="commission-info-right">
            <div class="info-section">
                <h4>Customer & Personnel</h4>
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $alterationCommission->customer->first_name }} {{ $alterationCommission->customer->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $alterationCommission->customer->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sales Assistant:</span>
                    <span class="info-value">{{ $alterationCommission->salesAssistant->first_name ?? 'Not assigned' }} {{ $alterationCommission->salesAssistant->last_name ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Craftsman:</span>
                    <span class="info-value">{{ $alterationCommission->craftsman->first_name ?? 'Not assigned' }} {{ $alterationCommission->craftsman->last_name ?? '' }}</span>
                </div>
                @if($alterationCommission->item)
                <div class="info-row">
                    <span class="info-label">Item:</span>
                    <span class="info-value">{{ $alterationCommission->item->name }} ({{ $alterationCommission->item->item_code }})</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="amount-section">
        <h4>Commission Amount</h4>
        <div class="amount-value">{{ number_format($alterationCommission->commission_amount, 2) }}</div>
        <div class="amount-currency">{{ $alterationCommission->currency->code }}</div>
        
        <div class="payment-progress">
            <div class="progress-bar">
                <div class="progress-fill progress-{{ $alterationCommission->payment_status }}" 
                     style="width: {{ $alterationCommission->progress_percentage }}%"></div>
                <div class="progress-text">{{ $alterationCommission->progress_percentage }}% {{ $alterationCommission->payment_status_label }}</div>
            </div>
            <div style="margin-top: 10px; font-size: 10px;">
                <span>Paid: {{ number_format($alterationCommission->paid_amount ?? 0, 2) }} | 
                Remaining: {{ number_format($alterationCommission->remaining_amount, 2) }}</span>
            </div>
        </div>
    </div>

    @if($alterationCommission->description)
    <div class="description-section">
        <h4>Description</h4>
        <div class="description-content">
            {{ $alterationCommission->description }}
        </div>
    </div>
    @endif

    @if($alterationCommission->notes)
    <div class="description-section">
        <h4>Notes</h4>
        <div class="description-content">
            {{ $alterationCommission->notes }}
        </div>
    </div>
    @endif

    <div class="timeline">
        <h4>Status Timeline</h4>
        <div class="timeline-item {{ $alterationCommission->status === 'pending' || $alterationCommission->status === 'in_progress' || $alterationCommission->status === 'completed' ? 'active' : '' }}">
            <div class="timeline-title">Pending</div>
            <div class="timeline-text">Commission created and waiting to be started</div>
        </div>

        @if($alterationCommission->status === 'in_progress' || $alterationCommission->status === 'completed')
        <div class="timeline-item {{ $alterationCommission->status === 'completed' ? 'active' : '' }}">
            <div class="timeline-title">In Progress</div>
            <div class="timeline-text">Alteration work is being performed</div>
        </div>
        @endif

        @if($alterationCommission->status === 'completed')
        <div class="timeline-item active">
            <div class="timeline-title">Completed</div>
            <div class="timeline-text">Alteration work is completed</div>
        </div>
        @endif

        @if($alterationCommission->status === 'cancelled')
        <div class="timeline-item active">
            <div class="timeline-title">Cancelled</div>
            <div class="timeline-text">Commission has been cancelled</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This document was generated automatically by the GemShop Alteration Commission Management System.</p>
        <p>For any questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
