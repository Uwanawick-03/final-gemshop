<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report Summary - {{ now()->format('M d, Y') }}</title>
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
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .summary-cell h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #333;
        }
        .summary-cell p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
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
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            margin-top: 50px;
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
        <h1>Sales Report Summary</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-cell">
                <h3>${{ number_format($summary['total_sales'], 2) }}</h3>
                <p>Total Sales</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['total_invoices']) }}</h3>
                <p>Total Invoices</p>
            </div>
            <div class="summary-cell">
                <h3>${{ number_format($summary['paid_amount'], 2) }}</h3>
                <p>Paid Amount</p>
            </div>
            <div class="summary-cell">
                <h3>${{ number_format($summary['overdue_amount'], 2) }}</h3>
                <p>Overdue Amount</p>
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-cell">
                <h3>${{ number_format($summary['this_month_sales'], 2) }}</h3>
                <p>This Month</p>
            </div>
            <div class="summary-cell">
                <h3>{{ $summary['growth_percentage'] >= 0 ? '+' : '' }}{{ number_format($summary['growth_percentage'], 1) }}%</h3>
                <p>Growth</p>
            </div>
            <div class="summary-cell">
                <h3>${{ number_format($summary['average_invoice_value'], 2) }}</h3>
                <p>Avg Invoice Value</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['overdue_invoices']) }}</h3>
                <p>Overdue Invoices</p>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    @if($recentSales->count() > 0)
    <div class="section">
        <h2>Recent Sales</h2>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $sale)
                <tr>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->customer->full_name ?? 'N/A' }}</td>
                    <td>{{ $sale->invoice_date->format('M d, Y') }}</td>
                    <td class="text-right">${{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'overdue' ? 'danger' : 'info') }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Gem Shop Management System</p>
        <p>For questions or support, please contact the system administrator</p>
    </div>
</body>
</html>
