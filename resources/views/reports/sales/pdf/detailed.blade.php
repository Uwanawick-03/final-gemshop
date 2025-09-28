<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detailed Sales Report - {{ now()->format('M d, Y') }}</title>
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
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detailed Sales Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Assistant</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ Str::limit($invoice->customer->full_name ?? 'N/A', 20) }}</td>
                <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                <td>{{ Str::limit($invoice->salesAssistant->full_name ?? 'N/A', 15) }}</td>
                <td class="text-right">${{ number_format($invoice->subtotal, 2) }}</td>
                <td class="text-right">${{ number_format($invoice->tax_amount, 2) }}</td>
                <td class="text-right">${{ number_format($invoice->discount_amount, 2) }}</td>
                <td class="text-right">${{ number_format($invoice->total_amount, 2) }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'info') }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </td>
                <td class="text-center">
                    @if($invoice->payment_method)
                        <span class="badge badge-{{ $invoice->payment_method == 'cash' ? 'success' : ($invoice->payment_method == 'card' ? 'info' : 'warning') }}">
                            {{ ucfirst($invoice->payment_method) }}
                        </span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the Gem Shop Management System</p>
        <p>Total Invoices: {{ $invoices->count() }} | Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>
