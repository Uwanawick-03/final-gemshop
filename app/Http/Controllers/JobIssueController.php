<?php

namespace App\Http\Controllers;

use App\Models\JobIssue;
use App\Models\Item;
use App\Models\Craftsman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class JobIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobIssues = JobIssue::with(['item', 'craftsman', 'assignedTo', 'resolvedBy'])
            ->whereNotNull('item_id')
            ->whereHas('item')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('job-issues.index', compact('jobIssues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('job-issues.create', compact('items', 'craftsmen', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'issue_type' => 'required|in:defect,delay,quality,material,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'issue_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_completion' => 'nullable|date|after:issue_date'
        ]);

        // Generate job number
        $jobNumber = 'JOB-' . strtoupper(Str::random(8));

        $jobIssue = JobIssue::create([
            'job_number' => $jobNumber,
            'item_id' => $request->item_id,
            'craftsman_id' => $request->craftsman_id,
            'issue_type' => $request->issue_type,
            'priority' => $request->priority,
            'issue_date' => $request->issue_date,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'estimated_completion' => $request->estimated_completion,
            'status' => 'open'
        ]);

        return redirect()->route('job-issues.show', $jobIssue)
            ->with('success', 'Job issue created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobIssue $jobIssue)
    {
        $jobIssue->load(['item', 'craftsman', 'assignedTo', 'resolvedBy']);
        return view('job-issues.show', compact('jobIssue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobIssue $jobIssue)
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        $craftsmen = Craftsman::where('is_active', true)->orderBy('first_name')->get();
        $users = User::orderBy('name')->get();

        return view('job-issues.edit', compact('jobIssue', 'items', 'craftsmen', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'craftsman_id' => 'nullable|exists:craftsmen,id',
            'issue_type' => 'required|in:defect,delay,quality,material,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'issue_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_completion' => 'nullable|date|after:issue_date',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string|max:1000',
            'resolved_date' => 'nullable|date',
            'actual_completion' => 'nullable|date'
        ]);

        $jobIssue->update($request->all());

        // If status is resolved or closed, set resolved_by and resolved_date
        if (in_array($request->status, ['resolved', 'closed']) && !$jobIssue->resolved_by) {
            $jobIssue->update([
                'resolved_by' => Auth::id(),
                'resolved_date' => now()->toDateString()
            ]);
        }

        return redirect()->route('job-issues.show', $jobIssue)
            ->with('success', 'Job issue updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobIssue $jobIssue)
    {
        $jobIssue->delete();

        return redirect()->route('job-issues.index')
            ->with('success', 'Job issue deleted successfully.');
    }

    /**
     * Update the status of a job issue
     */
    public function updateStatus(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string|max:1000'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If resolving or closing, set resolved_by and resolved_date
        if (in_array($request->status, ['resolved', 'closed'])) {
            $updateData['resolved_by'] = Auth::id();
            $updateData['resolved_date'] = now()->toDateString();
            $updateData['actual_completion'] = now()->toDateString();
        }

        if ($request->resolution_notes) {
            $updateData['resolution_notes'] = $request->resolution_notes;
        }

        $jobIssue->update($updateData);

        return redirect()->back()
            ->with('success', 'Job issue status updated successfully.');
    }

    /**
     * Bulk status update for multiple job issues
     */
    public function bulkStatusUpdate(Request $request)
    {
        $request->validate([
            'job_issue_ids' => 'required|array',
            'job_issue_ids.*' => 'exists:job_issues,id',
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If resolving or closing, set resolved_by and resolved_date
        if (in_array($request->status, ['resolved', 'closed'])) {
            $updateData['resolved_by'] = Auth::id();
            $updateData['resolved_date'] = now()->toDateString();
            $updateData['actual_completion'] = now()->toDateString();
        }

        JobIssue::whereIn('id', $request->job_issue_ids)->update($updateData);

        return redirect()->back()
            ->with('success', 'Selected job issues status updated successfully.');
    }

    /**
     * Export job issue as PDF
     */
    public function exportPdf(JobIssue $jobIssue)
    {
        $jobIssue->load(['item', 'craftsman', 'assignedTo', 'resolvedBy']);
        
        $pdf = \PDF::loadView('job-issues.pdf', compact('jobIssue'));
        return $pdf->download("job-issue-{$jobIssue->job_number}.pdf");
    }

    /**
     * Get job issues by status for dashboard
     */
    public function getByStatus($status)
    {
        $jobIssues = JobIssue::with(['item', 'craftsman', 'assignedTo'])
            ->where('status', $status)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($jobIssues);
    }
}
