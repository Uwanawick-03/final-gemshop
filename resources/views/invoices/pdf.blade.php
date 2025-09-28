<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container { 
            width: 100%; 
            margin: 0 auto; 
            max-width: 800px;
        }
        .header { 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info {
            flex: 1;
        }
        .company-info h1 { 
            margin: 0 0 10px 0; 
            color: #007bff;
            font-size: 28px;
        }
        .company-info p { 
            margin: 2px 0; 
            color: #666;
            font-size: 11px;
        }
        .invoice-info {
            text-align: right;
            flex: 1;
        }
        .invoice-info h2 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 24px;
        }
        .invoice-info p {
            margin: 2px 0;
            font-size: 11px;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bill-to, .invoice-details {
            flex: 1;
            margin: 0 20px;
        }
        .bill-to h3, .invoice-details h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .bill-to p, .invoice-details p {
            margin: 3px 0;
            font-size: 11px;
        }
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .items-table th, .items-table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .items-table th { 
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        .items-table td {
            font-size: 10px;
        }
        .total-row td { 
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .grand-total-row td {
            font-weight: bold;
            background-color: #e9ecef;
            font-size: 12px;
        }
        .footer { 
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .payment-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            text-transform: capitalize;
            font-size: 10px;
        }
        .bg-secondary { background-color: #6c757d; }
        .bg-info { background-color: #17a2b8; }
        .bg-warning { background-color: #ffc107; }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }
        .overdue-notice {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Gemshop') }}</h1>
                <p>{{ config('app.address', 'Your Business Address') }}</p>
                <p>Phone: {{ config('app.phone', 'Your Phone Number') }}</p>
                <p>Email: {{ config('app.email', 'your@email.com') }}</p>
                <p>Website: {{ config('app.url', 'www.yourwebsite.com') }}</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge bg-{{ $invoice->status_color }}">
                        {{ $invoice->status_label }}
                    </span>
                </p>
                @if($invoice->sales_order_id)
                    <p><strong>Sales Order:</strong> {{ $invoice->salesOrder?->so_number }}</p>
                @endif
            </div>
        </div>

        <!-- Overdue Notice -->
        @if($invoice->is_overdue)
            <div class="overdue-notice">
                <i class="fas fa-exclamation-triangle"></i>
                This invoice is {{ $invoice->days_overdue }} days overdue. Please remit payment immediately.
            </div>
        @endif

        <!-- Bill To & Invoice Details -->
        <div class="details-section">
            <div class="bill-to">
                <h3>Bill To:</h3>
                <p><strong>{{ $invoice->customer->full_name }}</strong></p>
                <p>{{ $invoice->customer->customer_code }}</p>
                @if($invoice->customer->email)
                    <p>{{ $invoice->customer->email }}</p>
                @endif
                @if($invoice->customer->phone)
                    <p>{{ $invoice->customer->phone }}</p>
                @endif
                @if($invoice->customer->full_address)
                    <p>{{ $invoice->customer->full_address }}</p>
                @endif
            </div>
            <div class="invoice-details">
                <h3>Invoice Details:</h3>
                <p><strong>Sales Assistant:</strong> {{ $invoice->salesAssistant->name }}</p>
                <p><strong>Currency:</strong> {{ $invoice->currency->code }} ({{ $invoice->currency->name }})</p>
                @if($invoice->currency_id && !$invoice->currency->is_base_currency)
                    <p><strong>Exchange Rate:</strong> {{ $invoice->exchange_rate }}</p>
                @endif
                @if($invoice->payment_terms)
                    <p><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</p>
                @endif
                @if($invoice->payment_method)
                    <p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
                @endif
                @if($invoice->createdBy)
                    <p><strong>Created By:</strong> {{ $invoice->createdBy->name }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <h3>Invoice Items:</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Item Description</th>
                    <th width="10%" class="text-center">Quantity</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="10%" class="text-right">Discount</th>
                    <th width="10%" class="text-right">Tax</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->transactionItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->item->name }}</strong>
                        @if($item->item->item_code)
                            <br><small>{{ $item->item->item_code }}</small>
                        @endif
                        @if($item->item->description)
                            <br><small>{{ $item->item->description }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }} {{ $item->item->unit }}</td>
                    <td class="text-right">{{ $invoice->currency->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">
                        @if($item->discount_amount > 0)
                            {{ $item->discount_percentage }}%<br>
                            <small>({{ $invoice->currency->symbol }}{{ number_format($item->discount_amount, 2) }})</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($item->tax_amount > 0)
                            {{ $item->tax_percentage }}%<br>
                            <small>({{ $invoice->currency->symbol }}{{ number_format($item->tax_amount, 2) }})</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right"><strong>{{ $invoice->currency->symbol }}{{ number_format($item->total_price, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right"><strong>{{ $invoice->currency->symbol }}{{ number_format($invoice->subtotal, 2) }}</strong></td>
                </tr>
                @if($invoice->discount_amount > 0)
                <tr class="total-row">
                    <td colspan="6" class="text-right"><strong>Total Discount:</strong></td>
                    <td class="text-right"><strong>-{{ $invoice->currency->symbol }}{{ number_format($invoice->discount_amount, 2) }}</strong></td>
                </tr>
                @endif
                @if($invoice->tax_amount > 0)
                <tr class="total-row">
                    <td colspan="6" class="text-right"><strong>Total Tax:</strong></td>
                    <td class="text-right"><strong>+{{ $invoice->currency->symbol }}{{ number_format($invoice->tax_amount, 2) }}</strong></td>
                </tr>
                @endif
                <tr class="grand-total-row">
                    <td colspan="6" class="text-right"><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>{{ $invoice->currency->symbol }}{{ number_format($invoice->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Payment Information -->
        <div class="payment-info">
            <h3 style="margin-top: 0;">Payment Information:</h3>
            <p><strong>Total Amount Due:</strong> {{ $invoice->currency->symbol }}{{ number_format($invoice->total_amount, 2) }}</p>
            @if($invoice->payment_terms)
                <p><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</p>
            @endif
            @if($invoice->payment_method)
                <p><strong>Preferred Payment Method:</strong> {{ $invoice->payment_method }}</p>
            @endif
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
        </div>

        <!-- Notes and Terms -->
        @if($invoice->notes || $invoice->terms_conditions)
        <div class="footer">
            @if($invoice->notes)
            <div class="mb-3">
                <h3>Notes:</h3>
                <p>{{ $invoice->notes }}</p>
            </div>
            @endif

            @if($invoice->terms_conditions)
            <div class="mb-3">
                <h3>Terms & Conditions:</h3>
                <p>{{ $invoice->terms_conditions }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Footer -->
        <div class="footer text-center">
            <p><strong>Thank you for your business!</strong></p>
            <p>For questions about this invoice, please contact us at {{ config('app.email', 'your@email.com') }}</p>
            <p><em>Generated on {{ date('M j, Y H:i A') }}</em></p>
        </div>
    </div>
</body>
</html>
