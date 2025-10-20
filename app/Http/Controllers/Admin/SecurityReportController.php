<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityReport;
use App\Models\AccountRecoveryRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

class SecurityReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display all security reports
     */
    public function index(Request $request)
    {
        $query = SecurityReport::with(['reporter', 'reportedUser', 'assignedAdmin']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->report_type) {
            $query->where('report_type', $request->report_type);
        }

        $reports = $query->latest()->paginate(20);

        $stats = [
            'pending' => SecurityReport::where('status', 'pending')->count(),
            'investigating' => SecurityReport::where('status', 'investigating')->count(),
            'critical' => SecurityReport::where('priority', 'critical')->count(),
            'high' => SecurityReport::where('priority', 'high')->count(),
        ];

        return view('admin.security.reports.index', compact('reports', 'stats'));
    }

    /**
     * Show specific report
     */
    public function show($id)
    {
        $report = SecurityReport::with(['reporter', 'reportedUser', 'assignedAdmin'])
            ->findOrFail($id);

        $admins = User::where('role', 'admin')->get();

        return view('admin.security.reports.show', compact('report', 'admins'));
    }

    /**
     * Update report status and assignment
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,investigating,resolved,dismissed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string',
            'resolution' => 'nullable|string',
        ]);

        $report = SecurityReport::findOrFail($id);

        $report->update([
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'assigned_to' => $validated['assigned_to'],
            'admin_notes' => $validated['admin_notes'],
            'resolution' => $validated['resolution'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);

        // TODO: Send email to reporter about status update

        return back()->with('success', 'Security report updated successfully.');
    }

    /**
     * Take action on reported user
     */
    public function takeAction(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:suspend,ban,warn,reset_password',
            'reason' => 'required|string',
            'duration' => 'nullable|integer|min:1'
        ]);

        $report = SecurityReport::findOrFail($id);
        $user = $report->reportedUser;

        if (!$user) {
            return back()->withErrors(['error' => 'No user associated with this report.']);
        }

        switch ($validated['action']) {
            case 'ban':
                $user->update(['status' => 'banned']);
                break;

            case 'suspend':
                $duration = $validated['duration'] ?? 7;
                $user->update([
                    'status' => 'suspended',
                    'suspended_until' => now()->addDays($duration)
                ]);
                break;

            case 'warn':
                // TODO: Create warning system
                break;

            case 'reset_password':
                // Force password reset
                Password::broker()->sendResetLink(['email' => $user->email]);
                break;
        }

        $report->update([
            'status' => 'resolved',
            'resolution' => "Action taken: {$validated['action']}. Reason: {$validated['reason']}",
            'resolved_at' => now()
        ]);

        // TODO: Send email to user about action

        return back()->with('success', 'Action taken successfully.');
    }

    /**
     * Account recovery requests
     */
    public function recoveryRequests(Request $request)
    {
        $query = AccountRecoveryRequest::with(['user', 'handler']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(20);

        $stats = [
            'pending' => AccountRecoveryRequest::where('status', 'pending')->count(),
            'investigating' => AccountRecoveryRequest::where('status', 'investigating')->count(),
        ];

        return view('admin.security.recovery.index', compact('requests', 'stats'));
    }

    /**
     * Show specific recovery request
     */
    public function showRecoveryRequest($id)
    {
        $request = AccountRecoveryRequest::with(['user', 'handler'])->findOrFail($id);

        return view('admin.security.recovery.show', compact('request'));
    }

    /**
     * Update recovery request
     */
    public function updateRecoveryRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,investigating,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $recovery = AccountRecoveryRequest::findOrFail($id);

        $recovery->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'handled_by' => auth()->id(),
            'resolved_at' => in_array($validated['status'], ['approved', 'rejected']) ? now() : null,
        ]);

        // If approved, send password reset link
        if ($validated['status'] === 'approved' && $recovery->user) {
            Password::broker()->sendResetLink(['email' => $recovery->email]);
        }

        // TODO: Send email notification to user

        return back()->with('success', 'Recovery request updated successfully.');
    }
}
