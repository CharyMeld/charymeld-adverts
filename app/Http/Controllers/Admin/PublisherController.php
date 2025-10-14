<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublisherProfile;
use App\Models\PublisherPayout;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display all publishers
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PublisherProfile::with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $publishers = $query->latest()->paginate(20);

        $stats = [
            'total' => PublisherProfile::count(),
            'pending' => PublisherProfile::where('status', 'pending')->count(),
            'approved' => PublisherProfile::where('status', 'approved')->count(),
            'rejected' => PublisherProfile::where('status', 'rejected')->count(),
        ];

        return view('admin.publishers.index', compact('publishers', 'stats', 'status'));
    }

    /**
     * Show publisher details
     */
    public function show(PublisherProfile $publisher)
    {
        $publisher->load(['user', 'zones', 'earnings']);

        $stats = [
            'total_earnings' => $publisher->earnings()->sum('amount'),
            'pending_earnings' => $publisher->earnings()->where('status', 'pending')->sum('amount'),
            'paid_earnings' => $publisher->earnings()->where('status', 'paid')->sum('amount'),
            'total_zones' => $publisher->zones()->count(),
            'active_zones' => $publisher->zones()->where('is_active', true)->count(),
        ];

        return view('admin.publishers.show', compact('publisher', 'stats'));
    }

    /**
     * Approve publisher
     */
    public function approve(PublisherProfile $publisher)
    {
        $publisher->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Publisher approved successfully!');
    }

    /**
     * Reject publisher
     */
    public function reject(Request $request, PublisherProfile $publisher)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $publisher->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Publisher rejected.');
    }

    /**
     * Update revenue share
     */
    public function updateRevenueShare(Request $request, PublisherProfile $publisher)
    {
        $validated = $request->validate([
            'revenue_share' => 'required|numeric|min:0|max:100',
        ]);

        $publisher->update([
            'revenue_share' => $validated['revenue_share'],
        ]);

        return back()->with('success', 'Revenue share updated successfully!');
    }

    /**
     * Show payouts
     */
    public function payouts(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PublisherPayout::with('publisher.user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payouts = $query->latest()->paginate(20);

        $stats = [
            'pending' => PublisherPayout::where('status', 'pending')->sum('amount'),
            'processing' => PublisherPayout::where('status', 'processing')->sum('amount'),
            'completed' => PublisherPayout::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.publishers.payouts', compact('payouts', 'stats', 'status'));
    }

    /**
     * Process payout
     */
    public function processPayout(PublisherPayout $payout)
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Payout is not pending.');
        }

        $payout->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Payout marked as processing.');
    }

    /**
     * Complete payout
     */
    public function completePayout(Request $request, PublisherPayout $payout)
    {
        if (!in_array($payout->status, ['pending', 'processing'])) {
            return back()->with('error', 'Payout cannot be completed.');
        }

        $data = [
            'status' => 'completed',
            'paid_at' => now(),
        ];

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $request->validate([
                'payment_proof' => 'file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            ]);

            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $data['payment_proof'] = $path;
        }

        // Add admin notes
        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->input('admin_notes');
        }

        $payout->update($data);

        // Mark earnings as paid
        PublisherEarning::where('payout_id', $payout->id)
            ->update(['status' => 'paid']);

        return back()->with('success', 'Payout completed successfully!');
    }

    /**
     * Cancel payout
     */
    public function cancelPayout(Request $request, PublisherPayout $payout)
    {
        if ($payout->status === 'completed') {
            return back()->with('error', 'Cannot cancel completed payout.');
        }

        $payout->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Reset earnings back to pending
        $payout->publisher->earnings()
            ->where('payout_id', $payout->id)
            ->update([
                'payout_id' => null,
                'status' => 'pending'
            ]);

        return back()->with('success', 'Payout cancelled.');
    }
}
