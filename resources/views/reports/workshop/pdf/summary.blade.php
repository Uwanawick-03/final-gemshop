<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Workshop Report Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
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
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .summary-item strong {
            font-weight: bold;
        }
        .alerts-section {
            margin-bottom: 30px;
        }
        .alerts-section h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .alert-category {
            margin-bottom: 20px;
        }
        .alert-category h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #666;
        }
        .alert-item {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 8px 12px;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .alert-item.urgent {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        .alert-item.overdue {
            border-left-color: #ffc107;
            background-color: #fff3cd;
        }
        .alert-item.expiring {
            border-left-color: #17a2b8;
            background-color: #d1ecf1;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .footer {
            margin-top: 40px;
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
        <h1>Workshop Report Summary</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>Job Issues</h3>
            <div class="summary-item">
                <span>Total Issues:</span>
                <strong>{{ $summary['total_job_issues'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Open Issues:</span>
                <strong>{{ $summary['open_job_issues'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Resolved Issues:</span>
                <strong>{{ $summary['resolved_job_issues'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Workshop Adjustments</h3>
            <div class="summary-item">
                <span>Total Adjustments:</span>
                <strong>{{ $summary['total_adjustments'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Pending:</span>
                <strong>{{ $summary['pending_adjustments'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Approved:</span>
                <strong>{{ $summary['approved_adjustments'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Finished Good Transfers</h3>
            <div class="summary-item">
                <span>Total Transfers:</span>
                <strong>{{ $summary['total_transfers'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Completed:</span>
                <strong>{{ $summary['completed_transfers'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Quality Passed:</span>
                <strong>{{ $summary['quality_check_passed'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Craftsman Returns</h3>
            <div class="summary-item">
                <span>Total Returns:</span>
                <strong>{{ $summary['total_returns'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Pending:</span>
                <strong>{{ $summary['pending_returns'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Completed:</span>
                <strong>{{ $summary['completed_returns'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>MTCs</h3>
            <div class="summary-item">
                <span>Total MTCs:</span>
                <strong>{{ $summary['total_mtcs'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Active:</span>
                <strong>{{ $summary['active_mtcs'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Expired:</span>
                <strong>{{ $summary['expired_mtcs'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Team</h3>
            <div class="summary-item">
                <span>Active Craftsmen:</span>
                <strong>{{ $summary['total_craftsmen'] }}</strong>
            </div>
        </div>
    </div>

    <!-- Workshop Alerts -->
    <div class="alerts-section">
        <h2>Workshop Alerts</h2>

        <!-- Urgent Job Issues -->
        <div class="alert-category">
            <h3>Urgent Job Issues</h3>
            @if($workshopAlerts['urgent_job_issues']->count() > 0)
                @foreach($workshopAlerts['urgent_job_issues'] as $issue)
                <div class="alert-item urgent">
                    <strong>Issue #{{ $issue->job_number }}</strong><br>
                    {{ $issue->item->name ?? 'N/A' }} - {{ $issue->issue_type }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No urgent job issues
                </div>
            @endif
        </div>

        <!-- Overdue Job Issues -->
        <div class="alert-category">
            <h3>Overdue Job Issues</h3>
            @if($workshopAlerts['overdue_job_issues']->count() > 0)
                @foreach($workshopAlerts['overdue_job_issues'] as $issue)
                <div class="alert-item overdue">
                    <strong>Issue #{{ $issue->job_number }}</strong><br>
                    {{ $issue->item->name ?? 'N/A' }} - {{ $issue->issue_date->format('M d, Y') }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No overdue issues
                </div>
            @endif
        </div>

        <!-- Pending Adjustments -->
        <div class="alert-category">
            <h3>Pending Adjustments</h3>
            @if($workshopAlerts['pending_adjustments']->count() > 0)
                @foreach($workshopAlerts['pending_adjustments'] as $adjustment)
                <div class="alert-item">
                    <strong>Adjustment #{{ $adjustment->reference_number }}</strong><br>
                    {{ $adjustment->item->name ?? 'N/A' }} - {{ $adjustment->adjustment_type }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No pending adjustments
                </div>
            @endif
        </div>

        <!-- Expiring MTCs -->
        <div class="alert-category">
            <h3>Expiring MTCs</h3>
            @if($workshopAlerts['expiring_mtcs']->count() > 0)
                @foreach($workshopAlerts['expiring_mtcs'] as $mtc)
                <div class="alert-item expiring">
                    <strong>MTC #{{ $mtc->mtc_number }}</strong><br>
                    {{ $mtc->item->name ?? 'N/A' }} - Expires {{ $mtc->expiry_date->format('M d, Y') }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No expiring MTCs
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>This report was generated automatically by the Workshop Management System</p>
        <p>For questions or concerns, please contact the workshop supervisor</p>
    </div>
</body>
</html>
