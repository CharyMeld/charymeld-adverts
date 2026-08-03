<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'advert']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gateway
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        // Search by reference
        if ($request->filled('q')) {
            $query->where('reference', 'like', "%{$request->q}%");
        }

        $transactions = $query->latest()->paginate(20);

        $stats = [
            'total_revenue' => Transaction::successful()->sum('amount'),
            'total_transactions' => Transaction::count(),
            'successful' => Transaction::successful()->count(),
            'pending' => Transaction::pending()->count(),
            'failed' => Transaction::failed()->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'advert']);
        return view('admin.transactions.show', compact('transaction'));
    }
}
