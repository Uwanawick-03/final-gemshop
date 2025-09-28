<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRN - {{ $grn->grn_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
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
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #666;
        }
        .grn-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            width: 48%;
        }
        .info-section h3 {
            background-color: #f5f5f5;
            padding: 8px;
            margin: 0 0 10px 0;
            font-size: 14px;
            border-left: 4px solid #007bff;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .totals-section {
            margin-left: auto;
            width: 300px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 10px;
            border: none;
        }
        .totals-table .total-row {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-section {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background-color: #6c757d; color: white; }
        .status-received { background-color: #17a2b8; color: white; }
        .status-verified { background-color: #ffc107; color: black; }
        .status-completed { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ config('app.name', 'Gemshop Management System') }}</div>
        <div class="document-title">GOODS RECEIPT NOTE (GRN)</div>
    </div>

    <!-- GRN Information -->
    <div class="grn-info">
        <div class="info-section">
            <h3>GRN Details</h3>
            <div class="info-row">
                <span class="info-label">GRN Number:</span>
                <span>{{ $grn->grn_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">GRN Date:</span>
                <span>{{ $grn->grn_date->format('M j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Received Date:</span>
                <span>{{ $grn->received_date->format('M j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ $grn->status }}">{{ ucfirst($grn->status) }}</span>
            </div>
            @if($grn->purchase_order)
            <div class="info-row">
                <span class="info-label">Purchase Order:</span>
                <span>{{ $grn->purchase_order->po_number }}</span>
            </div>
            @endif
        </div>

        <div class="info-section">
            <h3>Supplier Information</h3>
            <div class="info-row">
                <span class="info-label">Company:</span>
                <span>{{ $grn->supplier->company_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact:</span>
                <span>{{ $grn->supplier->contact_person }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span>{{ $grn->supplier->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $grn->supplier->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Received By:</span>
                <span>{{ $grn->user->name }}</span>
            </div>
        </div>
    </div>

    @if($grn->delivery_person || $grn->vehicle_number)
    <div class="info-section" style="margin-bottom: 20px;">
        <h3>Delivery Information</h3>
        @if($grn->delivery_person)
        <div class="info-row">
            <span class="info-label">Delivery Person:</span>
            <span>{{ $grn->delivery_person }}</span>
        </div>
        @endif
        @if($grn->vehicle_number)
        <div class="info-row">
            <span class="info-label">Vehicle Number:</span>
            <span>{{ $grn->vehicle_number }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grn->transactionItems as $item)
            <tr>
                <td>{{ $item->item->item_code }}</td>
                <td>{{ $item->item->name }}</td>
                <td class="text-center">{{ $item->quantity }} {{ $item->item->unit }}</td>
                <td class="text-right">{{ $grn->currency->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">
                    @if($item->discount_amount > 0)
                        {{ $grn->currency->symbol }}{{ number_format($item->discount_amount, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tax_amount > 0)
                        {{ $grn->currency->symbol }}{{ number_format($item->tax_amount, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-right"><strong>{{ $grn->currency->symbol }}{{ number_format($item->total_price, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right"><strong>{{ $grn->currency->symbol }}{{ number_format($grn->subtotal, 2) }}</strong></td>
            </tr>
            @if($grn->discount_amount > 0)
            <tr>
                <td colspan="6" class="text-right"><strong>Total Discount:</strong></td>
                <td class="text-right"><strong>{{ $grn->currency->symbol }}{{ number_format($grn->discount_amount, 2) }}</strong></td>
            </tr>
            @endif
            @if($grn->tax_amount > 0)
            <tr>
                <td colspan="6" class="text-right"><strong>Total Tax:</strong></td>
                <td class="text-right"><strong>{{ $grn->currency->symbol }}{{ number_format($grn->tax_amount, 2) }}</strong></td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="6" class="text-right"><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>{{ $grn->currency->symbol }}{{ number_format($grn->total_amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($grn->delivery_notes)
    <div style="margin-bottom: 20px;">
        <h3 style="background-color: #f5f5f5; padding: 8px; margin: 0 0 10px 0; font-size: 14px; border-left: 4px solid #007bff;">Delivery Notes</h3>
        <p style="margin: 0; padding: 10px; background-color: #f8f9fa; border: 1px solid #ddd;">{{ $grn->delivery_notes }}</p>
    </div>
    @endif

    @if($grn->notes)
    <div style="margin-bottom: 20px;">
        <h3 style="background-color: #f5f5f5; padding: 8px; margin: 0 0 10px 0; font-size: 14px; border-left: 4px solid #007bff;">Notes</h3>
        <p style="margin: 0; padding: 10px; background-color: #f8f9fa; border: 1px solid #ddd;">{{ $grn->notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="signature-section">
            <div class="signature-line">
                <strong>Received By</strong><br>
                {{ $grn->user->name }}<br>
                <small>{{ $grn->created_at->format('M j, Y g:i A') }}</small>
            </div>
        </div>
        <div class="signature-section">
            <div class="signature-line">
                <strong>Authorized Signature</strong>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        Generated on {{ now()->format('M j, Y g:i A') }} | {{ config('app.name', 'Gemshop Management System') }}
    </div>
</body>
</html>
