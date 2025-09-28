<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guide Listing Summary Report</title>
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
        .alert-item.warning {
            border-left-color: #ffc107;
            background-color: #fff3cd;
        }
        .alert-item.danger {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        .alert-item.info {
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
        <h1>Guide Listing Summary Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>Guide Overview</h3>
            <div class="summary-item">
                <span>Total Guides:</span>
                <strong>{{ $summary['total_guides'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Active Guides:</span>
                <strong>{{ $summary['active_guides'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Active Employment:</span>
                <strong>{{ $summary['active_employed'] }}</strong>
            </div>
            <div class="summary-item">
                <span>On Leave:</span>
                <strong>{{ $summary['on_leave'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>License Status</h3>
            <div class="summary-item">
                <span>Valid Licenses:</span>
                <strong>{{ $summary['valid_licenses'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Expiring Soon:</span>
                <strong>{{ $summary['expiring_licenses'] }}</strong>
            </div>
            <div class="summary-item">
                <span>Expired Licenses:</span>
                <strong>{{ $summary['expired_licenses'] }}</strong>
            </div>
            <div class="summary-item">
                <span>No License Info:</span>
                <strong>{{ $summary['no_licenses'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Employment Status</h3>
            <div class="summary-item">
                <span>Terminated:</span>
                <strong>{{ $summary['terminated'] }}</strong>
            </div>
            <div class="summary-item">
                <span>New This Month:</span>
                <strong>{{ $summary['new_guides_this_month'] }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <h3>Financial</h3>
            <div class="summary-item">
                <span>Avg Daily Rate:</span>
                <strong>${{ number_format($summary['avg_daily_rate'], 2) }}</strong>
            </div>
        </div>
    </div>

    <!-- Guide Alerts -->
    <div class="alerts-section">
        <h2>Guide Alerts</h2>

        <!-- Expiring Licenses -->
        <div class="alert-category">
            <h3>Licenses Expiring Soon (30 days)</h3>
            @if($guideAlerts['expiring_licenses']->count() > 0)
                @foreach($guideAlerts['expiring_licenses'] as $guide)
                <div class="alert-item warning">
                    <strong>{{ $guide->full_name }}</strong><br>
                    {{ $guide->guide_code }} - Expires {{ $guide->license_expiry->format('M d, Y') }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No licenses expiring soon
                </div>
            @endif
        </div>

        <!-- Expired Licenses -->
        <div class="alert-category">
            <h3>Expired Licenses</h3>
            @if($guideAlerts['expired_licenses']->count() > 0)
                @foreach($guideAlerts['expired_licenses'] as $guide)
                <div class="alert-item danger">
                    <strong>{{ $guide->full_name }}</strong><br>
                    {{ $guide->guide_code }} - Expired {{ $guide->license_expiry->format('M d, Y') }}
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No expired licenses
                </div>
            @endif
        </div>

        <!-- No Licenses -->
        <div class="alert-category">
            <h3>No License Information</h3>
            @if($guideAlerts['no_licenses']->count() > 0)
                @foreach($guideAlerts['no_licenses'] as $guide)
                <div class="alert-item info">
                    <strong>{{ $guide->full_name }}</strong><br>
                    {{ $guide->guide_code }} - No license on file
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    All guides have license information
                </div>
            @endif
        </div>

        <!-- On Leave -->
        <div class="alert-category">
            <h3>Currently on Leave</h3>
            @if($guideAlerts['on_leave']->count() > 0)
                @foreach($guideAlerts['on_leave'] as $guide)
                <div class="alert-item">
                    <strong>{{ $guide->full_name }}</strong><br>
                    {{ $guide->guide_code }} - On leave
                </div>
                @endforeach
            @else
                <div class="alert-item">
                    No guides currently on leave
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>This report was generated automatically by the Guide Management System</p>
        <p>For questions or concerns, please contact the guide management team</p>
    </div>
</body>
</html>
