<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Transfer - {{ $itemTransfer->reference_number }}</title>
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
        
        .transfer-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .transfer-info-left, .transfer-info-right {
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
        .status-in-transit { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        
        .reason-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .reason-restock { background-color: #d4edda; color: #155724; }
        .reason-sale-transfer { background-color: #d1ecf1; color: #0c5460; }
        .reason-repair { background-color: #fff3cd; color: #856404; }
        .reason-display { background-color: #cce5ff; color: #004085; }
        .reason-storage { background-color: #e2e3e5; color: #383d41; }
        .reason-damage { background-color: #f8d7da; color: #721c24; }
        .reason-other { background-color: #343a40; color: #fff; }
        
        .item-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .item-details h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .item-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .item-name {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }
        
        .item-code {
            color: #666;
            font-size: 11px;
        }
        
        .quantity-info {
            text-align: right;
        }
        
        .quantity-value {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }
        
        .location-flow {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .location-flow h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
        }
        
        .location-from, .location-to {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .location-from {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .location-to {
            background-color: #d4edda;
            color: #155724;
        }
        
        .arrow {
            margin: 0 20px;
            font-size: 16px;
            color: #666;
        }
        
        .notes-section {
            margin-top: 30px;
        }
        
        .notes-section h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .notes-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            min-height: 50px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .timeline {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Item Transfer</h1>
        <h2>{{ $itemTransfer->reference_number }}</h2>
    </div>

    <div class="company-info">
        <h3>GemShop</h3>
        <p>Item Transfer Management System</p>
        <p>Generated on: {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="transfer-info">
        <div class="transfer-info-left">
            <div class="info-section">
                <h4>Transfer Information</h4>
                <div class="info-row">
                    <span class="info-label">Reference Number:</span>
                    <span class="info-value">{{ $itemTransfer->reference_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transfer Date:</span>
                    <span class="info-value">{{ $itemTransfer->transfer_date->format('M d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge status-{{ $itemTransfer->status }}">
                        {{ $itemTransfer->status_label }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Reason:</span>
                    <span class="reason-badge reason-{{ $itemTransfer->reason }}">
                        {{ $itemTransfer->reason_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="transfer-info-right">
            <div class="info-section">
                <h4>Personnel</h4>
                <div class="info-row">
                    <span class="info-label">Transferred By:</span>
                    <span class="info-value">{{ $itemTransfer->transferredBy->name ?? 'Not assigned' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Received By:</span>
                    <span class="info-value">{{ $itemTransfer->receivedBy->name ?? 'Not received yet' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created By:</span>
                    <span class="info-value">{{ $itemTransfer->createdBy->name ?? 'System' }}</span>
                </div>
                @if($itemTransfer->received_at)
                <div class="info-row">
                    <span class="info-label">Received At:</span>
                    <span class="info-value">{{ $itemTransfer->received_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="item-details">
        <h4>Item Details</h4>
        <div class="item-info">
            <div>
                <div class="item-name">{{ $itemTransfer->item->name }}</div>
                <div class="item-code">Code: {{ $itemTransfer->item->item_code }}</div>
            </div>
            <div class="quantity-info">
                <div class="quantity-value">{{ number_format($itemTransfer->quantity, 3) }}</div>
                <div style="font-size: 10px; color: #666;">Quantity</div>
            </div>
        </div>
    </div>

    <div class="location-flow">
        <h4>Transfer Flow</h4>
        <div>
            <span class="location-from">{{ $itemTransfer->from_location }}</span>
            <span class="arrow">→</span>
            <span class="location-to">{{ $itemTransfer->to_location }}</span>
        </div>
    </div>

    @if($itemTransfer->notes)
    <div class="notes-section">
        <h4>Notes</h4>
        <div class="notes-content">
            {{ $itemTransfer->notes }}
        </div>
    </div>
    @endif

    <div class="timeline">
        <h4>Status Timeline</h4>
        <div class="timeline-item {{ $itemTransfer->status === 'pending' || $itemTransfer->status === 'in_transit' || $itemTransfer->status === 'completed' ? 'active' : '' }}">
            <div class="timeline-title">Pending</div>
            <div class="timeline-text">Transfer created and waiting to be processed</div>
        </div>

        @if($itemTransfer->status === 'in_transit' || $itemTransfer->status === 'completed')
        <div class="timeline-item {{ $itemTransfer->status === 'completed' ? 'active' : '' }}">
            <div class="timeline-title">In Transit</div>
            <div class="timeline-text">Items are being moved between locations</div>
        </div>
        @endif

        @if($itemTransfer->status === 'completed')
        <div class="timeline-item active">
            <div class="timeline-title">Completed</div>
            <div class="timeline-text">Transfer completed and stock updated</div>
        </div>
        @endif

        @if($itemTransfer->status === 'cancelled')
        <div class="timeline-item active">
            <div class="timeline-title">Cancelled</div>
            <div class="timeline-text">Transfer has been cancelled</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This document was generated automatically by the GemShop Item Transfer Management System.</p>
        <p>For any questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
