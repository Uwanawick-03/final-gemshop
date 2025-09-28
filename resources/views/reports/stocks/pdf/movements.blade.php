<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Movements Report - {{ now()->format('M d, Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #dee2e6;
        }
        .summary-cell:last-child {
            border-right: none;
        }
        .summary-cell h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
        }
        .summary-cell p {
            margin: 0;
            color: #666;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 4px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stock Movements Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <h3>{{ number_format($movements->where('quantity', '>', 0)->sum('quantity')) }}</h3>
                    <p>Total In</p>
                </div>
                <div class="summary-cell">
                    <h3>{{ number_format(abs($movements->where('quantity', '<', 0)->sum('quantity'))) }}</h3>
                    <p>Total Out</p>
                </div>
                <div class="summary-cell">
                    <h3>{{ $movements->count() }}</h3>
                    <p>Total Movements</p>
                </div>
                <div class="summary-cell">
                    <h3>{{ $movements->groupBy('type')->count() }}</h3>
                    <p>Movement Types</p>
                </div>
            </div>
        </div>
    </div>

    @if($movements->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reference</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
            <tr>
                <td>{{ \Carbon\Carbon::parse($movement->date)->format('M d, Y H:i') }}</td>
                <td>{{ $movement->item }}</td>
                <td class="text-center">
                    @php
                        $typeClass = match($movement->type) {
                            'Purchase' => 'success',
                            'Sale' => 'danger',
                            'Adjustment' => 'warning',
                            'Transfer' => 'info',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge badge-{{ $typeClass }}">{{ $movement->type }}</span>
                </td>
                <td class="text-center">
                    @if($movement->quantity > 0)
                        <span style="color: #28a745;">+{{ number_format($movement->quantity) }}</span>
                    @else
                        <span style="color: #dc3545;">{{ number_format($movement->quantity) }}</span>
                    @endif
                </td>
                <td>{{ $movement->reference }}</td>
                <td>{{ $movement->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <p>No stock movements found for the selected period.</p>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Gem Shop Management System</p>
        <p>Total Movements: {{ $movements->count() }} | Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>
