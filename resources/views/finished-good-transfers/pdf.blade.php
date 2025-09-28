<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finished Good Transfer Report - {{ $finishedGoodTransfer->reference_number }}</title>
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
        .status-quality-check { background-color: #17a2b8; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        
        .quality-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .quality-passed { background-color: #28a745; color: #fff; }
        .quality-failed { background-color: #dc3545; color: #fff; }
        .quality-not-checked { background-color: #6c757d; color: #fff; }
        
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
        <div class="report-title">Finished Good Transfer Report</div>
    </div>

    <div class="section">
        <div class="section-title">Transfer Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Reference Number:</td>
                <td><strong>{{ $finishedGoodTransfer->reference_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $finishedGoodTransfer->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $finishedGoodTransfer->status)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Transfer Date:</td>
                <td>{{ $finishedGoodTransfer->transfer_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">From Workshop:</td>
                <td>{{ $finishedGoodTransfer->from_workshop }}</td>
            </tr>
            <tr>
                <td class="label">To Location:</td>
                <td>{{ $finishedGoodTransfer->to_location }}</td>
            </tr>
            <tr>
                <td class="label">Quantity:</td>
                <td><strong>{{ $finishedGoodTransfer->quantity }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Item Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Item Name:</td>
                <td><strong>{{ $finishedGoodTransfer->item->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Item Code:</td>
                <td>{{ $finishedGoodTransfer->item->item_code }}</td>
            </tr>
            <tr>
                <td class="label">Category:</td>
                <td>{{ $finishedGoodTransfer->item->category }}</td>
            </tr>
            <tr>
                <td class="label">Material:</td>
                <td>{{ $finishedGoodTransfer->item->material }}</td>
            </tr>
            @if($finishedGoodTransfer->item->gemstone)
            <tr>
                <td class="label">Gemstone:</td>
                <td>{{ $finishedGoodTransfer->item->gemstone }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Current Stock:</td>
                <td>{{ $finishedGoodTransfer->item->current_stock }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Personnel Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Craftsman:</td>
                <td>{{ $finishedGoodTransfer->craftsman ? $finishedGoodTransfer->craftsman->full_name : 'Not assigned' }}</td>
            </tr>
            <tr>
                <td class="label">Transferred By:</td>
                <td>{{ $finishedGoodTransfer->transferredBy ? $finishedGoodTransfer->transferredBy->name : 'Not specified' }}</td>
            </tr>
            <tr>
                <td class="label">Received By:</td>
                <td>{{ $finishedGoodTransfer->receivedBy ? $finishedGoodTransfer->receivedBy->name : 'Not received yet' }}</td>
            </tr>
            @if($finishedGoodTransfer->qualityCheckBy)
            <tr>
                <td class="label">Quality Check By:</td>
                <td>{{ $finishedGoodTransfer->qualityCheckBy->name }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Quality Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Quality Check Result:</td>
                <td>
                    @if($finishedGoodTransfer->quality_check_passed !== null)
                        @if($finishedGoodTransfer->quality_check_passed)
                            <span class="quality-badge quality-passed">PASSED</span>
                        @else
                            <span class="quality-badge quality-failed">FAILED</span>
                        @endif
                    @else
                        <span class="quality-badge quality-not-checked">NOT CHECKED</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($finishedGoodTransfer->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="description-box">{{ $finishedGoodTransfer->notes }}</div>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        <p>Gem Shop Management System - Finished Good Transfer Report</p>
    </div>
</body>
</html>
