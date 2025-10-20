<?php

namespace App\Http\Controllers;

use App\Models\AccountRecoveryRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountRecoveryController extends Controller
{
    /**
     * Show account recovery request form
     */
    public function create()
    {
        return view('security.account-recovery.create');
    }

    /**
     * Store account recovery request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'description' => 'required|string|min:20',
            'alternative_email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'verification_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        // Upload verification document if provided
        $documentPath = null;
        if ($request->hasFile('verification_document')) {
            $documentPath = $request->file('verification_document')
                ->store('recovery-documents', 'private');
        }

        // Create recovery request
        $recovery = AccountRecoveryRequest::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'alternative_email' => $validated['alternative_email'],
            'phone' => $validated['phone'],
            'verification_document' => $documentPath,
            'user_id' => $user?->id,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // TODO: Send email notification to admins

        return redirect()->back()
            ->with('success', 'Your account recovery request has been submitted. Our team will review it shortly.');
    }

    /**
     * Show user's recovery requests
     */
    public function index()
    {
        $user = auth()->user();
        $requests = AccountRecoveryRequest::where('email', $user->email)
            ->orWhere('user_id', $user->id)
            ->latest()
            ->get();

        return view('security.account-recovery.index', compact('requests'));
    }
}
