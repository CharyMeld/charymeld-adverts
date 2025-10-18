<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterVerification;
use App\Mail\NewsletterWelcome;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
            'source' => 'nullable|string|in:footer,popup,homepage,sidebar',
        ]);

        // Create subscriber
        $subscriber = NewsletterSubscriber::create([
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'source' => $validated['source'] ?? 'website',
            'ip_address' => $request->ip(),
        ]);

        // Send verification email
        try {
            Mail::to($subscriber->email)->send(new NewsletterVerification($subscriber));
        } catch (\Exception $e) {
            \Log::error('Newsletter verification email failed: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Please check your email to verify your subscription.',
            ]);
        }

        return back()->with('success', 'Please check your email to verify your subscription.');
    }

    /**
     * Verify email
     */
    public function verify($token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification token.',
                ], 404);
            }

            return redirect()->route('home')->with('error', 'Invalid verification token.');
        }

        if ($subscriber->isVerified()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email already verified.',
                ]);
            }

            return redirect()->route('home')->with('info', 'Your email is already verified.');
        }

        // Verify subscriber
        $subscriber->verify();

        // Send welcome email
        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
        } catch (\Exception $e) {
            \Log::error('Newsletter welcome email failed: ' . $e->getMessage());
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully! Welcome to our newsletter.',
            ]);
        }

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to our newsletter.');
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe($email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (!$subscriber) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in our newsletter list.',
                ], 404);
            }

            return redirect()->route('home')->with('error', 'Email not found in our newsletter list.');
        }

        $subscriber->unsubscribe();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have been unsubscribed from our newsletter.',
            ]);
        }

        return view('newsletter.unsubscribed');
    }

    /**
     * Resubscribe to newsletter
     */
    public function resubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:newsletter_subscribers,email',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($subscriber->is_active) {
            return back()->with('info', 'You are already subscribed to our newsletter.');
        }

        $subscriber->update(['is_active' => true]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Welcome back! You have been resubscribed to our newsletter.',
            ]);
        }

        return back()->with('success', 'Welcome back! You have been resubscribed to our newsletter.');
    }
}
