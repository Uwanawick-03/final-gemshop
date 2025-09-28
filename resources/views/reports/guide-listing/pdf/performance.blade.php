<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guide Performance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 9px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Guide Performance Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Guide</th>
                <th>Code</th>
                <th>Performance Score</th>
                <th>Years of Service</th>
                <th>Performance Rating</th>
                <th>Employment Status</th>
                <th>License Status</th>
                <th>Daily Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performanceData as $index => $item)
            @php
                $guide = $item['guide'];
                $metrics = $item['metrics'];
            @endphp
            <tr>
                <td>#{{ $index + 1 }}</td>
                <td>{{ $guide->full_name }}</td>
                <td>{{ $guide->guide_code }}</td>
                <td>{{ number_format($metrics['performance_score'], 1) }}</td>
                <td>{{ $metrics['years_of_service'] }} years</td>
                <td>
                    <span class="badge badge-{{ $metrics['performance_rating']['color'] }}">
                        {{ $metrics['performance_rating']['rating'] }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $guide->employment_status === 'active' ? 'success' : ($guide->employment_status === 'inactive' ? 'secondary' : ($guide->employment_status === 'on_leave' ? 'warning' : 'danger')) }}">
                        {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $guide->license_status === 'Valid' ? 'success' : ($guide->license_status === 'Expiring Soon' ? 'warning' : ($guide->license_status === 'Expired' ? 'danger' : 'secondary')) }}">
                        {{ $guide->license_status }}
                    </span>
                </td>
                <td>
                    @if($guide->daily_rate)
                        ${{ number_format($guide->daily_rate, 2) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No performance data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Guides: {{ $performanceData->count() }}</p>
        <p>Average Performance Score: {{ number_format($performanceData->avg('metrics.performance_score'), 1) }}</p>
    </div>
</body>
</html>
