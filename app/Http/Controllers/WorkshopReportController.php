<?php

namespace App\Http\Controllers;

use App\Models\JobIssue;
use App\Models\WorkshopAdjustment;
use App\Models\FinishedGoodTransfer;
use App\Models\CraftsmanReturn;
use App\Models\Mtc;
use App\Models\Craftsman;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class WorkshopReportController extends Controller
{
    /**
     * Display the main workshop report dashboard
     */
    public function index()
    {
        // Get summary statistics
        $summary = $this->getWorkshopSummary();
        
        // Get workshop alerts
        $workshopAlerts = $this->getWorkshopAlerts();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get craftsman performance
        $craftsmanPerformance = $this->getCraftsmanPerformance();
        
        // Get workshop productivity
        $workshopProductivity = $this->getWorkshopProductivity();
        
        // Get quality metrics
        $qualityMetrics = $this->getQualityMetrics();
        
        // Get workshop locations
        $workshopLocations = $this->getWorkshopLocations();

        return view('reports.workshop.index', compact(
            'summary',
            'workshopAlerts',
            'recentActivities',
            'craftsmanPerformance',
            'workshopProductivity',
            'qualityMetrics',
            'workshopLocations'
        ));
    }

    /**
     * Detailed workshop report with filters
     */
    public function detailed(Request $request)
    {
        $reportType = $request->get('type', 'all'); // all, job_issues, adjustments, transfers, returns, mtcs
        
        $query = $this->buildWorkshopQuery($reportType, $request);
        $activities = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get filter options
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('reports.workshop.detailed', compact(
            'activities',
            'craftsmen',
            'items',
            'users',
            'reportType'
        ));
    }

    /**
     * Job issues report
     */
    public function jobIssues(Request $request)
    {
        $query = JobIssue::with(['item', 'craftsman', 'assignedTo', 'resolvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }

        if ($request->filled('craftsman_id')) {
            $query->where('craftsman_id', $request->craftsman_id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('issue_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('issue_date', '<=', $request->end_date);
        }

        $jobIssues = $query->orderBy('issue_date', 'desc')->paginate(50);

        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('reports.workshop.job-issues', compact(
            'jobIssues',
            'craftsmen',
            'users'
        ));
    }

    /**
     * Workshop adjustments report
     */
    public function adjustments(Request $request)
    {
        $query = WorkshopAdjustment::with(['item', 'craftsman', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('adjustment_type')) {
            $query->where('adjustment_type', $request->adjustment_type);
        }

        if ($request->filled('workshop_location')) {
            $query->where('workshop_location', $request->workshop_location);
        }

        if ($request->filled('craftsman_id')) {
            $query->where('craftsman_id', $request->craftsman_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('adjustment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('adjustment_date', '<=', $request->end_date);
        }

        $adjustments = $query->orderBy('adjustment_date', 'desc')->paginate(50);

        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.workshop.adjustments', compact(
            'adjustments',
            'craftsmen',
            'items'
        ));
    }

    /**
     * Finished good transfers report
     */
    public function transfers(Request $request)
    {
        $query = FinishedGoodTransfer::with(['item', 'craftsman', 'transferredBy', 'receivedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_workshop')) {
            $query->where('from_workshop', $request->from_workshop);
        }

        if ($request->filled('to_location')) {
            $query->where('to_location', $request->to_location);
        }

        if ($request->filled('craftsman_id')) {
            $query->where('craftsman_id', $request->craftsman_id);
        }

        if ($request->filled('quality_check_passed')) {
            $query->where('quality_check_passed', $request->quality_check_passed);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transfer_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transfer_date', '<=', $request->end_date);
        }

        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(50);

        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.workshop.transfers', compact(
            'transfers',
            'craftsmen',
            'items'
        ));
    }

    /**
     * Craftsman returns report
     */
    public function returns(Request $request)
    {
        $query = CraftsmanReturn::with(['craftsman', 'item', 'processedBy', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('return_type')) {
            $query->where('return_type', $request->return_type);
        }

        if ($request->filled('craftsman_id')) {
            $query->where('craftsman_id', $request->craftsman_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('return_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('return_date', '<=', $request->end_date);
        }

        $returns = $query->orderBy('return_date', 'desc')->paginate(50);

        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $items = Item::where('is_active', true)->orderBy('name')->get();

        return view('reports.workshop.returns', compact(
            'returns',
            'craftsmen',
            'items'
        ));
    }

    /**
     * MTCs report
     */
    public function mtcs(Request $request)
    {
        $query = Mtc::query();

        // Check if columns exist before filtering
        $hasStatusColumn = Schema::hasColumn('mtcs', 'status');
        $hasCustomerIdColumn = Schema::hasColumn('mtcs', 'customer_id');
        $hasSalesAssistantIdColumn = Schema::hasColumn('mtcs', 'sales_assistant_id');
        $hasIssueDateColumn = Schema::hasColumn('mtcs', 'issue_date');
        $hasExpiryDateColumn = Schema::hasColumn('mtcs', 'expiry_date');

        if ($request->filled('status') && $hasStatusColumn) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id') && $hasCustomerIdColumn) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('sales_assistant_id') && $hasSalesAssistantIdColumn) {
            $query->where('sales_assistant_id', $request->sales_assistant_id);
        }

        if ($request->filled('expiring_soon') && $hasExpiryDateColumn && $hasStatusColumn) {
            $query->expiringSoon();
        }

        if ($request->filled('expired') && $hasStatusColumn) {
            $query->expired();
        }

        if ($request->filled('start_date') && $hasIssueDateColumn) {
            $query->whereDate('issue_date', '>=', $request->start_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date') && $hasIssueDateColumn) {
            $query->whereDate('issue_date', '<=', $request->end_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $mtcs = $query->orderBy($hasIssueDateColumn ? 'issue_date' : 'created_at', 'desc')->paginate(50);

        $customers = \App\Models\Customer::orderBy('first_name')->get();
        $salesAssistants = \App\Models\SalesAssistant::where('is_active', true)->orderBy('first_name')->get();

        return view('reports.workshop.mtcs', compact(
            'mtcs',
            'customers',
            'salesAssistants'
        ));
    }

    /**
     * Export workshop report to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        switch ($reportType) {
            case 'job_issues':
                $jobIssues = JobIssue::with(['item', 'craftsman'])->get();
                $pdf = \PDF::loadView('reports.workshop.pdf.job-issues', compact('jobIssues'));
                break;
            case 'adjustments':
                $adjustments = WorkshopAdjustment::with(['item', 'craftsman'])->get();
                $pdf = \PDF::loadView('reports.workshop.pdf.adjustments', compact('adjustments'));
                break;
            case 'transfers':
                $transfers = FinishedGoodTransfer::with(['item', 'craftsman'])->get();
                $pdf = \PDF::loadView('reports.workshop.pdf.transfers', compact('transfers'));
                break;
            case 'returns':
                $returns = CraftsmanReturn::with(['craftsman', 'item'])->get();
                $pdf = \PDF::loadView('reports.workshop.pdf.returns', compact('returns'));
                break;
            case 'mtcs':
                $mtcs = Mtc::with(['item', 'customer'])->get();
                $pdf = \PDF::loadView('reports.workshop.pdf.mtcs', compact('mtcs'));
                break;
            default:
                $summary = $this->getWorkshopSummary();
                $workshopAlerts = $this->getWorkshopAlerts();
                $pdf = \PDF::loadView('reports.workshop.pdf.summary', compact('summary', 'workshopAlerts'));
        }

        return $pdf->download("workshop-report-{$reportType}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export workshop report to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        // This would typically use Laravel Excel package
        // For now, we'll return a CSV
        return $this->exportCsv($request);
    }

    /**
     * Export workshop report to CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        $filename = "workshop-report-{$reportType}-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportType) {
            $handle = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'job_issues':
                    fputcsv($handle, ['Job Number', 'Item', 'Craftsman', 'Issue Type', 'Priority', 'Status', 'Issue Date']);
                    JobIssue::with(['item', 'craftsman'])->chunk(100, function($issues) use ($handle) {
                        foreach ($issues as $issue) {
                            fputcsv($handle, [
                                $issue->job_number,
                                $issue->item->name ?? 'N/A',
                                $issue->craftsman->full_name ?? 'N/A',
                                $issue->issue_type,
                                $issue->priority,
                                $issue->status,
                                $issue->issue_date->format('Y-m-d')
                            ]);
                        }
                    });
                    break;
                default:
                    fputcsv($handle, ['Type', 'Description', 'Date', 'Status']);
                    // Export recent activities
                    $activities = $this->getRecentActivities();
                    foreach ($activities as $activity) {
                        fputcsv($handle, [
                            $activity->type ?? 'N/A',
                            $activity->description ?? 'N/A',
                            $activity->date ?? 'N/A',
                            $activity->status ?? 'N/A'
                        ]);
                    }
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get workshop summary statistics
     */
    private function getWorkshopSummary()
    {
        $totalJobIssues = JobIssue::count();
        $openJobIssues = JobIssue::where('status', 'open')->count();
        $resolvedJobIssues = JobIssue::where('status', 'resolved')->count();
        
        $totalAdjustments = WorkshopAdjustment::count();
        $pendingAdjustments = WorkshopAdjustment::where('status', 'pending')->count();
        $approvedAdjustments = WorkshopAdjustment::where('status', 'approved')->count();
        
        $totalTransfers = FinishedGoodTransfer::count();
        $completedTransfers = FinishedGoodTransfer::where('status', 'completed')->count();
        $qualityCheckPassed = FinishedGoodTransfer::where('quality_check_passed', true)->count();
        
        $totalReturns = CraftsmanReturn::count();
        $pendingReturns = CraftsmanReturn::where('status', 'pending')->count();
        $completedReturns = CraftsmanReturn::where('status', 'completed')->count();
        
        $totalMtcs = Mtc::count();
        
        // Check if MTCs table has status column
        $hasMtcStatusColumn = Schema::hasColumn('mtcs', 'status');
        $activeMtcs = $hasMtcStatusColumn ? Mtc::where('status', 'active')->count() : 0;
        $expiredMtcs = $hasMtcStatusColumn ? Mtc::where('status', 'expired')->count() : 0;
        
        $totalCraftsmen = Craftsman::where('is_active', true)->count();

        return [
            'total_job_issues' => $totalJobIssues,
            'open_job_issues' => $openJobIssues,
            'resolved_job_issues' => $resolvedJobIssues,
            'total_adjustments' => $totalAdjustments,
            'pending_adjustments' => $pendingAdjustments,
            'approved_adjustments' => $approvedAdjustments,
            'total_transfers' => $totalTransfers,
            'completed_transfers' => $completedTransfers,
            'quality_check_passed' => $qualityCheckPassed,
            'total_returns' => $totalReturns,
            'pending_returns' => $pendingReturns,
            'completed_returns' => $completedReturns,
            'total_mtcs' => $totalMtcs,
            'active_mtcs' => $activeMtcs,
            'expired_mtcs' => $expiredMtcs,
            'total_craftsmen' => $totalCraftsmen
        ];
    }

    /**
     * Get workshop alerts
     */
    private function getWorkshopAlerts()
    {
        $alerts = [
            'urgent_job_issues' => JobIssue::where('priority', 'urgent')
                ->where('status', '!=', 'resolved')
                ->orderBy('issue_date', 'desc')
                ->limit(5)
                ->get(),
            'overdue_job_issues' => JobIssue::where('status', 'open')
                ->where('issue_date', '<', now()->subDays(7))
                ->orderBy('issue_date', 'asc')
                ->limit(5)
                ->get(),
            'pending_adjustments' => WorkshopAdjustment::where('status', 'pending')
                ->orderBy('adjustment_date', 'desc')
                ->limit(5)
                ->get()
        ];

        // Check if MTCs table has required columns
        $hasMtcExpiryColumn = Schema::hasColumn('mtcs', 'expiry_date');
        $hasMtcStatusColumn = Schema::hasColumn('mtcs', 'status');
        
        if ($hasMtcExpiryColumn && $hasMtcStatusColumn) {
            $alerts['expiring_mtcs'] = Mtc::expiringSoon()
                ->orderBy('expiry_date', 'asc')
                ->limit(5)
                ->get();
        } else {
            $alerts['expiring_mtcs'] = collect();
        }

        return $alerts;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Get recent job issues
        $jobIssues = JobIssue::with(['item', 'craftsman'])
            ->whereNotNull('item_id')
            ->whereHas('item')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($issue) {
                return (object)[
                    'type' => 'Job Issue',
                    'description' => "Issue #{$issue->job_number} - " . ($issue->item ? $issue->item->name : 'Unknown Item'),
                    'date' => $issue->issue_date,
                    'status' => $issue->status,
                    'priority' => $issue->priority
                ];
            });
        
        // Get recent workshop adjustments
        $adjustments = WorkshopAdjustment::with(['item', 'craftsman'])
            ->whereNotNull('item_id')
            ->whereHas('item')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($adjustment) {
                return (object)[
                    'type' => 'Workshop Adjustment',
                    'description' => "Adjustment #{$adjustment->reference_number} - " . ($adjustment->item ? $adjustment->item->name : 'Unknown Item'),
                    'date' => $adjustment->adjustment_date,
                    'status' => $adjustment->status,
                    'priority' => null
                ];
            });
        
        // Get recent transfers
        $transfers = FinishedGoodTransfer::with(['item', 'craftsman'])
            ->whereNotNull('item_id')
            ->whereHas('item')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($transfer) {
                return (object)[
                    'type' => 'Finished Good Transfer',
                    'description' => "Transfer #{$transfer->reference_number} - " . ($transfer->item ? $transfer->item->name : 'Unknown Item'),
                    'date' => $transfer->transfer_date,
                    'status' => $transfer->status,
                    'priority' => null
                ];
            });
        
        return $activities->merge($jobIssues)->merge($adjustments)->merge($transfers)
            ->sortByDesc('date')
            ->take(15);
    }

    /**
     * Get craftsman performance
     */
    private function getCraftsmanPerformance()
    {
        return Craftsman::where('is_active', true)
            ->withCount(['jobIssues as total_issues'])
            ->withCount(['jobIssues as resolved_issues' => function($query) {
                $query->where('status', 'resolved');
            }])
            ->orderBy('total_issues', 'desc')
            ->limit(10)
            ->get()
            ->map(function($craftsman) {
                // Add manual counts for relationships that might not exist
                $craftsman->total_adjustments = \App\Models\WorkshopAdjustment::where('craftsman_id', $craftsman->id)->count();
                $craftsman->total_transfers = \App\Models\FinishedGoodTransfer::where('craftsman_id', $craftsman->id)->count();
                return $craftsman;
            });
    }

    /**
     * Get workshop productivity
     */
    private function getWorkshopProductivity()
    {
        return [
            'issues_resolved_today' => JobIssue::where('status', 'resolved')
                ->whereDate('resolved_date', today())
                ->count(),
            'adjustments_today' => WorkshopAdjustment::whereDate('adjustment_date', today())
                ->count(),
            'transfers_today' => FinishedGoodTransfer::whereDate('transfer_date', today())
                ->count(),
            'returns_today' => CraftsmanReturn::whereDate('return_date', today())
                ->count()
        ];
    }

    /**
     * Get quality metrics
     */
    private function getQualityMetrics()
    {
        $totalTransfers = FinishedGoodTransfer::count();
        $qualityPassed = FinishedGoodTransfer::where('quality_check_passed', true)->count();
        $qualityRate = $totalTransfers > 0 ? ($qualityPassed / $totalTransfers) * 100 : 0;
        
        return [
            'quality_pass_rate' => $qualityRate,
            'total_quality_checks' => $totalTransfers,
            'passed_quality_checks' => $qualityPassed,
            'failed_quality_checks' => $totalTransfers - $qualityPassed
        ];
    }

    /**
     * Get workshop locations
     */
    private function getWorkshopLocations()
    {
        $locations = collect();
        
        // Get unique workshop locations from adjustments
        $adjustmentLocations = WorkshopAdjustment::distinct()
            ->pluck('workshop_location')
            ->filter()
            ->map(function($location) {
                return (object)[
                    'name' => $location,
                    'type' => 'Adjustment Location',
                    'count' => WorkshopAdjustment::where('workshop_location', $location)->count()
                ];
            });
        
        // Get unique from_workshop locations from transfers
        $transferLocations = FinishedGoodTransfer::distinct()
            ->pluck('from_workshop')
            ->filter()
            ->map(function($location) {
                return (object)[
                    'name' => $location,
                    'type' => 'Transfer Location',
                    'count' => FinishedGoodTransfer::where('from_workshop', $location)->count()
                ];
            });
        
        return $locations->merge($adjustmentLocations)->merge($transferLocations)
            ->groupBy('name')
            ->map(function($group) {
                $first = $group->first();
                $first->count = $group->sum('count');
                return $first;
            })
            ->sortByDesc('count')
            ->values();
    }

    /**
     * Build workshop query based on report type
     */
    private function buildWorkshopQuery($reportType, $request)
    {
        switch ($reportType) {
            case 'job_issues':
                return JobIssue::with(['item', 'craftsman', 'assignedTo']);
            case 'adjustments':
                return WorkshopAdjustment::with(['item', 'craftsman', 'approvedBy']);
            case 'transfers':
                return FinishedGoodTransfer::with(['item', 'craftsman', 'transferredBy']);
            case 'returns':
                return CraftsmanReturn::with(['craftsman', 'item', 'processedBy']);
            case 'mtcs':
                return Mtc::with(['item', 'customer', 'salesAssistant']);
            default:
                // Return a union of all activities
                return collect();
        }
    }
}
