<?php

namespace App\Http\Controllers;

use App\Models\SecurityReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecurityReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show create report form
     */
    public function create()
    {
        $reportTypes = [
            'hacked_account' => 'Hacked Account',
            'spam' => 'Spam',
            'harassment' => 'Harassment',
            'fraud' => 'Fraud',
            'inappropriate_content' => 'Inappropriate Content',
            'phishing' => 'Phishing',
            'other' => 'Other'
        ];

        $users = User::where('id', '!=', auth()->id())
            ->select('id', 'name', 'email')
            ->get();

        return view('security.reports.create', compact('reportTypes', 'users'));
    }

    /**
     * Store new security report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:hacked_account,spam,harassment,fraud,inappropriate_content,phishing,other',
            'reported_user_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'url' => 'nullable|url',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        // Upload evidence if provided
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')
                ->store('security-evidence', 'private');
        }

        // Auto-assign priority based on report type
        $priority = match($validated['report_type']) {
            'hacked_account', 'phishing', 'fraud' => 'critical',
            'harassment', 'inappropriate_content' => 'high',
            'spam' => 'medium',
            default => 'low'
        };

        // Create security report
        $report = SecurityReport::create([
            'reporter_id' => auth()->id(),
            'reported_user_id' => $validated['reported_user_id'],
            'report_type' => $validated['report_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'url' => $validated['url'],
            'evidence' => $evidencePath,
            'priority' => $priority,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        // TODO: Send email notification to admins

        return redirect()->route('security.reports.index')
            ->with('success', 'Your security report has been submitted successfully. Our team will review it shortly.');
    }

    /**
     * Show user's reports
     */
    public function index()
    {
        $reports = SecurityReport::where('reporter_id', auth()->id())
            ->with(['reportedUser', 'assignedAdmin'])
            ->latest()
            ->paginate(20);

        return view('security.reports.index', compact('reports'));
    }

    /**
     * Show specific report
     */
    public function show($id)
    {
        $report = SecurityReport::where('reporter_id', auth()->id())
            ->with(['reportedUser', 'assignedAdmin'])
            ->findOrFail($id);

        return view('security.reports.show', compact('report'));
    }
}
