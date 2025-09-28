<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detailed Guide Listing Report</title>
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
        <h1>Detailed Guide Listing Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>Country</th>
                <th>Languages</th>
                <th>Service Areas</th>
                <th>Employment Status</th>
                <th>Daily Rate</th>
                <th>License Status</th>
                <th>Joined Date</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guides as $guide)
            <tr>
                <td>{{ $guide->guide_code }}</td>
                <td>{{ $guide->full_name }}</td>
                <td>{{ $guide->email ?? '' }}</td>
                <td>{{ $guide->phone }}</td>
                <td>{{ $guide->city ?? '' }}</td>
                <td>{{ $guide->country ?? '' }}</td>
                <td>{{ $guide->languages_list }}</td>
                <td>{{ $guide->service_areas_list }}</td>
                <td>
                    <span class="badge badge-{{ $guide->employment_status === 'active' ? 'success' : ($guide->employment_status === 'inactive' ? 'secondary' : ($guide->employment_status === 'on_leave' ? 'warning' : 'danger')) }}">
                        {{ ucfirst(str_replace('_', ' ', $guide->employment_status)) }}
                    </span>
                </td>
                <td>
                    @if($guide->daily_rate)
                        ${{ number_format($guide->daily_rate, 2) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $guide->license_status === 'Valid' ? 'success' : ($guide->license_status === 'Expiring Soon' ? 'warning' : ($guide->license_status === 'Expired' ? 'danger' : 'secondary')) }}">
                        {{ $guide->license_status }}
                    </span>
                </td>
                <td>{{ $guide->joined_date ? $guide->joined_date->format('M d, Y') : 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $guide->is_active ? 'success' : 'secondary' }}">
                        {{ $guide->is_active ? 'Yes' : 'No' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="text-center">No guides found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Guides: {{ $guides->count() }}</p>
    </div>
</body>
</html>
