<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Craftsman Return Report - {{ $craftsmanReturn->return_number }}</title>
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
        .status-approved { background-color: #17a2b8; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        
        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .type-defective { background-color: #dc3545; color: #fff; }
        .type-unused-material { background-color: #17a2b8; color: #fff; }
        .type-excess { background-color: #28a745; color: #fff; }
        .type-quality-issue { background-color: #ffc107; color: #000; }
        
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
        <div class="report-title">Craftsman Return Report</div>
    </div>

    <div class="section">
        <div class="section-title">Return Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Return Number:</td>
                <td><strong>{{ $craftsmanReturn->return_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="status-badge status-{{ $craftsmanReturn->status }}">
                        {{ ucfirst($craftsmanReturn->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Return Type:</td>
                <td>
                    <span class="type-badge type-{{ str_replace('_', '-', $craftsmanReturn->return_type) }}">
                        {{ ucfirst(str_replace('_', ' ', $craftsmanReturn->return_type)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Return Date:</td>
                <td>{{ $craftsmanReturn->return_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Quantity:</td>
                <td><strong>{{ $craftsmanReturn->quantity }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Craftsman Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Name:</td>
                <td><strong>{{ $craftsmanReturn->craftsman->full_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Code:</td>
                <td>{{ $craftsmanReturn->craftsman->craftsman_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Specialization:</td>
                <td>{{ $craftsmanReturn->craftsman->specialization ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td>{{ $craftsmanReturn->craftsman->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>{{ $craftsmanReturn->craftsman->is_active ? 'Active' : 'Inactive' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Item Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Item Name:</td>
                <td><strong>{{ $craftsmanReturn->item->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Item Code:</td>
                <td>{{ $craftsmanReturn->item->item_code }}</td>
            </tr>
            <tr>
                <td class="label">Category:</td>
                <td>{{ $craftsmanReturn->item->category }}</td>
            </tr>
            <tr>
                <td class="label">Material:</td>
                <td>{{ $craftsmanReturn->item->material }}</td>
            </tr>
            @if($craftsmanReturn->item->gemstone)
            <tr>
                <td class="label">Gemstone:</td>
                <td>{{ $craftsmanReturn->item->gemstone }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Current Stock:</td>
                <td>{{ $craftsmanReturn->item->current_stock }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Personnel Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Processed By:</td>
                <td>{{ $craftsmanReturn->processedBy ? $craftsmanReturn->processedBy->name : 'Not processed yet' }}</td>
            </tr>
            <tr>
                <td class="label">Approved By:</td>
                <td>{{ $craftsmanReturn->approvedBy ? $craftsmanReturn->approvedBy->name : 'Not approved yet' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Reason</div>
        <div class="description-box">{{ $craftsmanReturn->reason }}</div>
    </div>

    @if($craftsmanReturn->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="description-box">{{ $craftsmanReturn->notes }}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Stock Impact</div>
        <table class="info-table">
            @if($craftsmanReturn->status === 'completed')
                @if(in_array($craftsmanReturn->return_type, ['defective', 'quality_issue']))
                <tr>
                    <td class="label">Impact:</td>
                    <td><strong>Stock Decreased by {{ $craftsmanReturn->quantity }} units</strong></td>
                </tr>
                @elseif(in_array($craftsmanReturn->return_type, ['unused_material', 'excess']))
                <tr>
                    <td class="label">Impact:</td>
                    <td><strong>Stock Increased by {{ $craftsmanReturn->quantity }} units</strong></td>
                </tr>
                @endif
            @else
            <tr>
                <td class="label">Impact:</td>
                <td><strong>No impact - Return not yet completed</strong></td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        <p>Gem Shop Management System - Craftsman Return Report</p>
    </div>
</body>
</html>
