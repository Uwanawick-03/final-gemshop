<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supplier Return {{ $supplierReturn->return_number }}</title>
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
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .return-info {
            text-align: right;
        }
        
        .return-info h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #333;
        }
        
        .return-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .return-details p {
            margin: 5px 0;
        }
        
        .supplier-info {
            margin-bottom: 30px;
        }
        
        .supplier-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        
        .supplier-info p {
            margin: 3px 0;
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
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .summary {
            width: 300px;
            margin-left: auto;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            padding: 5px 0;
        }
        
        .summary-row.total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .notes {
            margin-top: 30px;
        }
        
        .notes h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        
        .notes p {
            margin: 5px 0;
            color: #666;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #ffc107; color: black; }
        .status-approved { background-color: #17a2b8; color: white; }
        .status-completed { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        
        .reason-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .reason-defective { background-color: #dc3545; color: white; }
        .reason-wrong_item { background-color: #ffc107; color: black; }
        .reason-overstock { background-color: #17a2b8; color: white; }
        .reason-damaged { background-color: #dc3545; color: white; }
        .reason-quality_issue { background-color: #ffc107; color: black; }
        .reason-other { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1>GemShop</h1>
            <p>123 Business Street</p>
            <p>City, State 12345</p>
            <p>Phone: (123) 456-7890</p>
            <p>Email: info@gemshop.com</p>
        </div>
        
        <div class="return-info">
            <h2>SUPPLIER RETURN</h2>
            <div class="return-details">
                <p><strong>Return #:</strong> {{ $supplierReturn->return_number }}</p>
                <p><strong>Date:</strong> {{ $supplierReturn->return_date->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $supplierReturn->status }}">
                        {{ $supplierReturn->status_label }}
                    </span>
                </p>
                <p><strong>Reason:</strong> 
                    <span class="reason-badge reason-{{ $supplierReturn->reason }}">
                        {{ $supplierReturn->reason_label }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Supplier Info -->
    <div class="supplier-info">
        <h3>Supplier:</h3>
        <p><strong>{{ $supplierReturn->supplier->company_name }}</strong></p>
        <p>{{ $supplierReturn->supplier->supplier_code }}</p>
        @if($supplierReturn->supplier->contact_person)
            <p>Contact: {{ $supplierReturn->supplier->contact_person }}</p>
        @endif
        @if($supplierReturn->supplier->email)
            <p>{{ $supplierReturn->supplier->email }}</p>
        @endif
        @if($supplierReturn->supplier->phone)
            <p>{{ $supplierReturn->supplier->phone }}</p>
        @endif
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplierReturn->transactionItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item->name }}</strong><br>
                        <small>{{ $item->item->item_code }}</small>
                    </td>
                    <td class="text-center">{{ number_format($item->quantity, 3) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">
                        @if($item->discount_percentage > 0)
                            {{ number_format($item->discount_percentage, 1) }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->tax_percentage > 0)
                            {{ number_format($item->tax_percentage, 1) }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>{{ number_format($supplierReturn->subtotal, 2) }} {{ $supplierReturn->currency->code }}</span>
        </div>
        <div class="summary-row">
            <span>Discount:</span>
            <span>{{ number_format($supplierReturn->discount_amount, 2) }} {{ $supplierReturn->currency->code }}</span>
        </div>
        <div class="summary-row">
            <span>Tax:</span>
            <span>{{ number_format($supplierReturn->tax_amount, 2) }} {{ $supplierReturn->currency->code }}</span>
        </div>
        <div class="summary-row total">
            <span>Total:</span>
            <span>{{ number_format($supplierReturn->total_amount, 2) }} {{ $supplierReturn->currency->code }}</span>
        </div>
    </div>

    <!-- Notes -->
    @if($supplierReturn->notes)
        <div class="notes">
            <h3>Notes:</h3>
            <p>{{ $supplierReturn->notes }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html>
