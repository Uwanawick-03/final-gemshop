<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report Summary - {{ now()->format('M d, Y') }}</title>
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
        <h1>Stock Report Summary</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-cell">
                <h3>{{ number_format($summary['total_items']) }}</h3>
                <p>Total Items</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['active_items']) }}</h3>
                <p>Active Items</p>
            </div>
            <div class="summary-cell">
                <h3>${{ number_format($summary['total_stock_value'], 2) }}</h3>
                <p>Total Stock Value</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['low_stock_items']) }}</h3>
                <p>Low Stock Items</p>
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-cell">
                <h3>{{ number_format($summary['out_of_stock_items']) }}</h3>
                <p>Out of Stock</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['total_quantity']) }}</h3>
                <p>Total Quantity</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['categories_count']) }}</h3>
                <p>Categories</p>
            </div>
            <div class="summary-cell">
                <h3>{{ number_format($summary['materials_count']) }}</h3>
                <p>Materials</p>
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    @if($lowStockItems->count() > 0)
    <div class="section">
        <h2>Low Stock Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Name</th>
                    <th>Current Stock</th>
                    <th>Min Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockItems as $item)
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->current_stock }}</td>
                    <td class="text-center">{{ $item->minimum_stock }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->stock_status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $item->stock_status)) }}
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
