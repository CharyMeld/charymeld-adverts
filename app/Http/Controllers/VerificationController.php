<?php

namespace App\Http\Controllers;

use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Show verification form
     */
    public function index()
    {
        $verification = auth()->user()->verification;

        return view('verification.index', compact('verification'));
    }

    /**
     * Store or update verification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'nin' => 'required|string|size:11|unique:user_verifications,nin,' . (auth()->user()->verification->id ?? 'NULL'),
            'nin_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'required|string|max:100',
            'id_type' => 'nullable|in:drivers_license,voters_card,international_passport,other',
            'id_number' => 'nullable|string|max:50',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proof_of_address_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:20',
            'account_name' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_registration_number' => 'nullable|string|max:50',
            'business_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('nin_document')) {
            $validated['nin_document'] = $request->file('nin_document')->store('verifications/nin', 'public');
        }

        if ($request->hasFile('id_document')) {
            $validated['id_document'] = $request->file('id_document')->store('verifications/id', 'public');
        }

        if ($request->hasFile('proof_of_address_document')) {
            $validated['proof_of_address_document'] = $request->file('proof_of_address_document')->store('verifications/address', 'public');
        }

        if ($request->hasFile('business_document')) {
            $validated['business_document'] = $request->file('business_document')->store('verifications/business', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['verification_status'] = 'pending'; // Auto-submit for review

        // Create or update verification
        $verification = UserVerification::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return redirect()->route('verification.index')
            ->with('success', 'Verification information submitted successfully! Our team will review your information within 24-48 hours.');
    }

    /**
     * Admin: List all verifications
     */
    public function adminIndex(Request $request)
    {
        $query = UserVerification::with('user');

        // Filter by status
        if ($request->status) {
            $query->where('verification_status', $request->status);
        }

        $verifications = $query->latest()->paginate(20);

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Admin: Show verification details
     */
    public function adminShow(UserVerification $verification)
    {
        $verification->load('user');

        return view('admin.verifications.show', compact('verification'));
    }

    /**
     * Admin: Approve verification
     */
    public function approve(Request $request, UserVerification $verification)
    {
        $validated = $request->validate([
            'verify_nin' => 'boolean',
            'verify_id' => 'boolean',
            'verify_address' => 'boolean',
            'verify_bank' => 'boolean',
        ]);

        $verification->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'is_nin_verified' => $validated['verify_nin'] ?? false,
            'is_id_verified' => $validated['verify_id'] ?? false,
            'is_address_verified' => $validated['verify_address'] ?? false,
            'is_bank_verified' => $validated['verify_bank'] ?? false,
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', 'User verification approved successfully!');
    }

    /**
     * Admin: Reject verification
     */
    public function reject(Request $request, UserVerification $verification)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $verification->update([
            'verification_status' => 'rejected',
            'verified_at' => null,
            'verified_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Verification rejected. User will be notified.');
    }
}
