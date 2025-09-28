<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Workshop Adjustment Report - {{ $workshopAdjustment->reference_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 18px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        
        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .type-material-used { background-color: #17a2b8; color: #fff; }
        .type-scrap { background-color: #6c757d; color: #fff; }
        .type-defective { background-color: #dc3545; color: #fff; }
        .type-correction { background-color: #28a745; color: #fff; }
        
        .description-box {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 50px;
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
        <div class="company-name">Gem Shop Management System</div>
        <div class="report-title">Workshop Adjustment Report</div>
    </div>

    <div class="section">
        <div class="section-title">Adjustment Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Reference Number:</td>
                <td><strong>{{ $workshopAdjustment->reference_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="status-badge status-{{ $workshopAdjustment->status }}">
                        {{ ucfirst($workshopAdjustment->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Adjustment Type:</td>
                <td>
                    <span class="type-badge type-{{ str_replace('_', '-', $workshopAdjustment->adjustment_type) }}">
                        {{ ucfirst(str_replace('_', ' ', $workshopAdjustment->adjustment_type)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Adjustment Date:</td>
                <td>{{ $workshopAdjustment->adjustment_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Workshop Location:</td>
                <td>{{ $workshopAdjustment->workshop_location }}</td>
            </tr>
            <tr>
                <td class="label">Quantity:</td>
                <td><strong>{{ $workshopAdjustment->quantity }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Item Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Item Name:</td>
                <td><strong>{{ $workshopAdjustment->item->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Item Code:</td>
                <td>{{ $workshopAdjustment->item->item_code }}</td>
            </tr>
            <tr>
                <td class="label">Category:</td>
                <td>{{ $workshopAdjustment->item->category }}</td>
            </tr>
            <tr>
                <td class="label">Material:</td>
                <td>{{ $workshopAdjustment->item->material }}</td>
            </tr>
            @if($workshopAdjustment->item->gemstone)
            <tr>
                <td class="label">Gemstone:</td>
                <td>{{ $workshopAdjustment->item->gemstone }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Current Stock:</td>
                <td>{{ $workshopAdjustment->item->current_stock }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Personnel Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Craftsman:</td>
                <td>{{ $workshopAdjustment->craftsman ? $workshopAdjustment->craftsman->full_name : 'Not assigned' }}</td>
            </tr>
            <tr>
                <td class="label">Approved By:</td>
                <td>{{ $workshopAdjustment->approvedBy ? $workshopAdjustment->approvedBy->name : 'Not approved yet' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Reason</div>
        <div class="description-box">{{ $workshopAdjustment->reason }}</div>
    </div>

    @if($workshopAdjustment->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="description-box">{{ $workshopAdjustment->notes }}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Stock Impact</div>
        <table class="info-table">
            @if($workshopAdjustment->status === 'approved')
                @if(in_array($workshopAdjustment->adjustment_type, ['material_used', 'scrap', 'defective']))
                <tr>
                    <td class="label">Impact:</td>
                    <td><strong>Stock Decreased by {{ $workshopAdjustment->quantity }} units</strong></td>
                </tr>
                @elseif($workshopAdjustment->adjustment_type === 'correction')
                <tr>
                    <td class="label">Impact:</td>
                    <td><strong>Stock Increased by {{ $workshopAdjustment->quantity }} units</strong></td>
                </tr>
                @endif
            @else
            <tr>
                <td class="label">Impact:</td>
                <td><strong>No impact - Adjustment not yet approved</strong></td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        <p>Gem Shop Management System - Workshop Adjustment Report</p>
    </div>
</body>
</html>
