<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initializePaystack(Request $request)
    {
        $request->validate([
            'advert_id' => 'required|exists:adverts,id',
            'plan' => 'required|in:regular,featured,premium',
        ]);

        $advert = Advert::findOrFail($request->advert_id);

        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $plans = [
            'regular' => 1000,
            'featured' => 3000,
            'premium' => 5000,
        ];

        $amount = $plans[$request->plan] * 100; // Convert to kobo
        $reference = 'TXN_' . Str::random(16);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'advert_id' => $advert->id,
            'amount' => $plans[$request->plan],
            'gateway' => 'paystack',
            'reference' => $reference,
            'status' => 'pending',
            'metadata' => json_encode(['plan' => $request->plan]),
        ]);

        // Initialize Paystack payment
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => route('payment.paystack.callback'),
            'metadata' => [
                'transaction_id' => $transaction->id,
                'advert_id' => $advert->id,
                'plan' => $request->plan,
            ],
        ]);

        if ($response->successful() && $response->json('status')) {
            return redirect($response->json('data.authorization_url'));
        }

        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    public function paystackCallback(Request $request)
    {
        $reference = $request->reference;

        if (!$reference) {
            return redirect()->route('advertiser.dashboard')
                ->with('error', 'Invalid payment reference');
        }

        // Verify transaction
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response->json('data.status') === 'success') {
            $data = $response->json('data');

            $transaction = Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->status === 'pending') {
                $transaction->update(['status' => 'success']);

                // Activate advert
                $advert = $transaction->advert;
                $plan = json_decode($transaction->metadata, true)['plan'];
                $duration = $plan === 'premium' ? 60 : 30;

                $advert->update([
                    'plan' => $plan,
                    'is_active' => true,
                    'status' => 'pending', // Still needs admin approval
                    'expires_at' => now()->addDays($duration),
                ]);

                return redirect()->route('advertiser.adverts.index')
                    ->with('success', 'Payment successful! Your advert will be reviewed by admin.');
            }
        }

        return redirect()->route('advertiser.dashboard')
            ->with('error', 'Payment verification failed');
    }

    public function paystackWebhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $body = $request->getContent();

        if ($signature !== hash_hmac('sha512', $body, env('PAYSTACK_SECRET_KEY'))) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->all();

        if ($event['event'] === 'charge.success') {
            $reference = $event['data']['reference'];

            $transaction = Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->status === 'pending') {
                $transaction->update(['status' => 'success']);

                // Activate advert
                $advert = $transaction->advert;
                $plan = json_decode($transaction->metadata, true)['plan'];
                $duration = $plan === 'premium' ? 60 : 30;

                $advert->update([
                    'plan' => $plan,
                    'is_active' => true,
                    'status' => 'pending',
                    'expires_at' => now()->addDays($duration),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function initializeFlutterwave(Request $request)
    {
        $request->validate([
            'advert_id' => 'required|exists:adverts,id',
            'plan' => 'required|in:regular,featured,premium',
        ]);

        $advert = Advert::findOrFail($request->advert_id);

        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $plans = [
            'regular' => 1000,
            'featured' => 3000,
            'premium' => 5000,
        ];

        $reference = 'FLW_' . Str::random(16);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'advert_id' => $advert->id,
            'amount' => $plans[$request->plan],
            'gateway' => 'flutterwave',
            'reference' => $reference,
            'status' => 'pending',
            'metadata' => json_encode(['plan' => $request->plan]),
        ]);

        // Initialize Flutterwave payment
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.flutterwave.com/v3/payments', [
            'tx_ref' => $reference,
            'amount' => $plans[$request->plan],
            'currency' => 'NGN',
            'redirect_url' => route('payment.flutterwave.callback'),
            'customer' => [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name,
            ],
            'customizations' => [
                'title' => 'CharyMeld Adverts',
                'description' => "Payment for {$request->plan} plan",
            ],
            'meta' => [
                'transaction_id' => $transaction->id,
                'advert_id' => $advert->id,
                'plan' => $request->plan,
            ],
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            return redirect($response->json('data.link'));
        }

        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    public function flutterwaveCallback(Request $request)
    {
        $transactionId = $request->transaction_id;

        if (!$transactionId) {
            return redirect()->route('advertiser.dashboard')
                ->with('error', 'Invalid payment transaction');
        }

        // Verify transaction
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
        ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        if ($response->successful() && $response->json('data.status') === 'successful') {
            $data = $response->json('data');
            $reference = $data['tx_ref'];

            $transaction = Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->status === 'pending') {
                $transaction->update(['status' => 'success']);

                // Activate advert
                $advert = $transaction->advert;
                $plan = json_decode($transaction->metadata, true)['plan'];
                $duration = $plan === 'premium' ? 60 : 30;

                $advert->update([
                    'plan' => $plan,
                    'is_active' => true,
                    'status' => 'pending',
                    'expires_at' => now()->addDays($duration),
                ]);

                return redirect()->route('advertiser.adverts.index')
                    ->with('success', 'Payment successful! Your advert will be reviewed by admin.');
            }
        }

        return redirect()->route('advertiser.dashboard')
            ->with('error', 'Payment verification failed');
    }

    public function flutterwaveWebhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('verif-hash');

        if ($signature !== env('FLUTTERWAVE_SECRET_HASH')) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->all();

        if ($event['event'] === 'charge.completed' && $event['data']['status'] === 'successful') {
            $reference = $event['data']['tx_ref'];

            $transaction = Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->status === 'pending') {
                $transaction->update(['status' => 'success']);

                // Activate advert
                $advert = $transaction->advert;
                $plan = json_decode($transaction->metadata, true)['plan'];
                $duration = $plan === 'premium' ? 60 : 30;

                $advert->update([
                    'plan' => $plan,
                    'is_active' => true,
                    'status' => 'pending',
                    'expires_at' => now()->addDays($duration),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
