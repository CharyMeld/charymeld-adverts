<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_adverts' => $user->adverts()->count(),
            'active_adverts' => $user->adverts()->active()->count(),
            'pending_adverts' => $user->adverts()->where('status', 'pending')->count(),
            'total_views' => $user->adverts()->sum('views'),
            'total_spent' => $user->transactions()->successful()->sum('amount'),
        ];

        $recentAdverts = $user->adverts()
            ->with(['category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = $user->transactions()
            ->with('advert')
            ->latest()
            ->take(5)
            ->get();

        return view('advertiser.dashboard', compact('stats', 'recentAdverts', 'recentTransactions'));
    }
}
