<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Return - {{ $customerReturn->return_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            color: #666;
        }
        .return-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            width: 48%;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #d4af37;
            font-size: 16px;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #d4af37;
            color: white;
            font-weight: bold;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #d4af37;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .status-processed { background-color: #17a2b8; color: #fff; }
        .status-refunded { background-color: #6f42c1; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GemShop</div>
        <div class="document-title">Customer Return</div>
    </div>

    <div class="return-info">
        <div class="info-section">
            <h3>Return Information</h3>
            <p><strong>Return Number:</strong> {{ $customerReturn->return_number }}</p>
            <p><strong>Return Date:</strong> {{ $customerReturn->return_date->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $customerReturn->status }}">
                    {{ ucfirst($customerReturn->status) }}
                </span>
            </p>
            <p><strong>Reason:</strong> {{ $customerReturn->reason }}</p>
            @if($customerReturn->notes)
                <p><strong>Notes:</strong> {{ $customerReturn->notes }}</p>
            @endif
        </div>
        
        <div class="info-section">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $customerReturn->customer->full_name }}</p>
            <p><strong>Customer Code:</strong> {{ $customerReturn->customer->customer_code }}</p>
            @if($customerReturn->customer->email)
                <p><strong>Email:</strong> {{ $customerReturn->customer->email }}</p>
            @endif
            @if($customerReturn->customer->phone)
                <p><strong>Phone:</strong> {{ $customerReturn->customer->phone }}</p>
            @endif
            @if($customerReturn->customer->full_address)
                <p><strong>Address:</strong> {{ $customerReturn->customer->full_address }}</p>
            @endif
        </div>
    </div>

    <h3>Return Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customerReturn->transactionItems as $item)
            <tr>
                <td>{{ $item->item->item_code }}</td>
                <td>{{ $item->item->name }}</td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ number_format($item->unit_price, 2) }} {{ $customerReturn->currency->code ?? 'LKR' }}</td>
                <td>{{ number_format($item->total_price, 2) }} {{ $customerReturn->currency->code ?? 'LKR' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #d4af37; color: white; font-weight: bold;">
                <td colspan="4" style="text-align: right;">Total Amount:</td>
                <td>{{ number_format($customerReturn->total_amount, 2) }} {{ $customerReturn->currency->code ?? 'LKR' }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>
