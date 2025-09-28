<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Issue Report - {{ $jobIssue->job_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
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
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 18px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-open { background-color: #ffc107; color: #000; }
        .status-in-progress { background-color: #17a2b8; color: #fff; }
        .status-resolved { background-color: #28a745; color: #fff; }
        .status-closed { background-color: #6c757d; color: #fff; }
        
        .priority-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-low { background-color: #28a745; color: #fff; }
        .priority-medium { background-color: #17a2b8; color: #fff; }
        .priority-high { background-color: #ffc107; color: #000; }
        .priority-urgent { background-color: #dc3545; color: #fff; }
        
        .description-box {
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Gem Shop Management System</div>
        <div class="report-title">Job Issue Report</div>
    </div>

    <div class="section">
        <div class="section-title">Job Issue Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Job Number:</td>
                <td><strong>{{ $jobIssue->job_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="status-badge status-{{ str_replace('_', '-', $jobIssue->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $jobIssue->status)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Issue Type:</td>
                <td>{{ ucfirst($jobIssue->issue_type) }}</td>
            </tr>
            <tr>
                <td class="label">Priority:</td>
                <td>
                    <span class="priority-badge priority-{{ $jobIssue->priority }}">
                        {{ ucfirst($jobIssue->priority) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Issue Date:</td>
                <td>{{ $jobIssue->issue_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Estimated Completion:</td>
                <td>{{ $jobIssue->estimated_completion ? $jobIssue->estimated_completion->format('M d, Y') : 'Not set' }}</td>
            </tr>
            <tr>
                <td class="label">Actual Completion:</td>
                <td>{{ $jobIssue->actual_completion ? $jobIssue->actual_completion->format('M d, Y') : 'Not completed' }}</td>
            </tr>
            <tr>
                <td class="label">Resolved Date:</td>
                <td>{{ $jobIssue->resolved_date ? $jobIssue->resolved_date->format('M d, Y') : 'Not resolved' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Item Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Item Name:</td>
                <td><strong>{{ $jobIssue->item->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Item Code:</td>
                <td>{{ $jobIssue->item->item_code }}</td>
            </tr>
            <tr>
                <td class="label">Category:</td>
                <td>{{ $jobIssue->item->category }}</td>
            </tr>
            <tr>
                <td class="label">Material:</td>
                <td>{{ $jobIssue->item->material }}</td>
            </tr>
            @if($jobIssue->item->gemstone)
            <tr>
                <td class="label">Gemstone:</td>
                <td>{{ $jobIssue->item->gemstone }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Current Stock:</td>
                <td>{{ $jobIssue->item->current_stock }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Assignment Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Craftsman:</td>
                <td>{{ $jobIssue->craftsman ? $jobIssue->craftsman->name : 'Not assigned' }}</td>
            </tr>
            <tr>
                <td class="label">Assigned To:</td>
                <td>{{ $jobIssue->assignedTo ? $jobIssue->assignedTo->name : 'Unassigned' }}</td>
            </tr>
            @if($jobIssue->resolvedBy)
            <tr>
                <td class="label">Resolved By:</td>
                <td>{{ $jobIssue->resolvedBy->name }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Issue Description</div>
        <div class="description-box">{{ $jobIssue->description }}</div>
    </div>

    @if($jobIssue->resolution_notes)
    <div class="section">
        <div class="section-title">Resolution Notes</div>
        <div class="description-box">{{ $jobIssue->resolution_notes }}</div>
    </div>
    @endif

    @if($jobIssue->resolution_time)
    <div class="section">
        <div class="section-title">Resolution Statistics</div>
        <table class="info-table">
            <tr>
                <td class="label">Resolution Time:</td>
                <td>{{ $jobIssue->resolution_time }} days</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        <p>Gem Shop Management System - Job Issue Report</p>
    </div>
</body>
</html>
