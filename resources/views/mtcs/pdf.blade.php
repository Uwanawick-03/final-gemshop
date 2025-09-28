<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MTC - {{ $mtc->mtc_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
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
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .content {
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th,
        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .info-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Material Transfer Certificate</h1>
        <p>MTC Number: {{ $mtc->mtc_number }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <th>MTC Number</th>
                <td>{{ $mtc->mtc_number }}</td>
                <th>Status</th>
                <td>
                    <span class="badge badge-{{ $mtc->status_badge }}">
                        {{ ucfirst($mtc->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Item</th>
                <td>{{ $mtc->item->name }} ({{ $mtc->item->item_code }})</td>
                <th>Issue Date</th>
                <td>{{ $mtc->issue_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $mtc->customer->full_name }} ({{ $mtc->customer->customer_code }})</td>
                <th>Expiry Date</th>
                <td>{{ $mtc->expiry_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <th>Sales Assistant</th>
                <td>{{ $mtc->salesAssistant->full_name }} ({{ $mtc->salesAssistant->employee_code }})</td>
                <th>Days Until Expiry</th>
                <td>
                    @if($mtc->days_until_expiry !== null)
                        @if($mtc->days_until_expiry < 0)
                            Expired {{ abs($mtc->days_until_expiry) }} days ago
                        @elseif($mtc->days_until_expiry <= 30)
                            {{ $mtc->days_until_expiry }} days (Expiring Soon)
                        @else
                            {{ $mtc->days_until_expiry }} days
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Purchase Price</th>
                <td>${{ number_format($mtc->purchase_price, 2) }}</td>
                <th>Selling Price</th>
                <td>${{ number_format($mtc->selling_price, 2) }}</td>
            </tr>
            <tr>
                <th>Profit Margin</th>
                <td>
                    @php
                        $margin = $mtc->selling_price - $mtc->purchase_price;
                        $marginPercent = $mtc->purchase_price > 0 ? ($margin / $mtc->purchase_price) * 100 : 0;
                    @endphp
                    ${{ number_format($margin, 2) }} ({{ number_format($marginPercent, 1) }}%)
                </td>
                <th>Created</th>
                <td>{{ $mtc->created_at->format('M d, Y \a\t g:i A') }}</td>
            </tr>
        </table>

        @if($mtc->notes)
        <div>
            <h3>Notes:</h3>
            <p>{{ $mtc->notes }}</p>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This certificate was generated automatically by the MTC Management System</p>
        <p>For questions or concerns, please contact the sales team</p>
    </div>
</body>
</html>
