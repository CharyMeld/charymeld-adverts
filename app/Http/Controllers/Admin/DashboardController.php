<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'advertiser')->count(),
            'new_users_this_month' => User::where('role', 'advertiser')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_adverts' => Advert::count(),
            'new_adverts_this_month' => Advert::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'pending_adverts' => Advert::where('status', 'pending')->count(),
            'active_adverts' => Advert::active()->count(),
            'rejected_adverts' => Advert::where('status', 'rejected')->count(),
            'featured_adverts' => Advert::where('plan', 'featured')->count(),
            'premium_adverts' => Advert::where('plan', 'premium')->count(),
            'total_revenue' => Transaction::successful()->sum('amount'),
            'revenue_this_month' => Transaction::successful()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_categories' => Category::count(),
            'total_blogs' => Blog::count(),
        ];

        // Monthly revenue for chart
        $monthlyRevenue = Transaction::successful()
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // User growth for chart
        $userGrowth = User::where('role', 'advertiser')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        // Recent adverts
        $recentAdverts = Advert::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        // Pending adverts
        $pendingAdverts = Advert::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        // Recent users
        $recentUsers = User::where('role', 'advertiser')
            ->latest()
            ->take(10)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'advert'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'userGrowth',
            'recentAdverts',
            'pendingAdverts',
            'recentUsers',
            'recentTransactions'
        ));
    }
}
