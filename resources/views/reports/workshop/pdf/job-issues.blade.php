<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Issues Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
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
            font-size: 20px;
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Job Issues Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Job Number</th>
                <th>Item</th>
                <th>Craftsman</th>
                <th>Issue Type</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Issue Date</th>
                <th>Assigned To</th>
                <th>Resolution Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobIssues as $issue)
            <tr>
                <td>{{ $issue->job_number }}</td>
                <td>{{ $issue->item->name ?? 'N/A' }}</td>
                <td>{{ $issue->craftsman->full_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($issue->issue_type) }}</td>
                <td>
                    <span class="badge badge-{{ $issue->priority === 'urgent' ? 'danger' : ($issue->priority === 'high' ? 'warning' : 'info') }}">
                        {{ ucfirst($issue->priority) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $issue->status === 'open' ? 'warning' : ($issue->status === 'resolved' ? 'success' : 'info') }}">
                        {{ ucfirst($issue->status) }}
                    </span>
                </td>
                <td>{{ $issue->issue_date->format('M d, Y') }}</td>
                <td>{{ $issue->assignedTo->name ?? 'Unassigned' }}</td>
                <td>
                    @if($issue->resolution_time)
                        {{ $issue->resolution_time }} days
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No job issues found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Job Issues: {{ $jobIssues->count() }}</p>
    </div>
</body>
</html>
