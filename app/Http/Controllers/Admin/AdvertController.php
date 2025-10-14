<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    public function index(Request $request)
    {
        $query = Advert::with(['user', 'category', 'primaryImage']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($q) use ($keyword) {
                      $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                  });
            });
        }

        $adverts = $query->latest()->paginate(20);

        return view('admin.adverts.index', compact('adverts'));
    }

    public function show(Advert $advert)
    {
        $advert->load(['user', 'category', 'images', 'transactions']);
        return view('admin.adverts.show', compact('advert'));
    }

    public function approve(Advert $advert)
    {
        $advert->update([
            'status' => 'approved',
            'published_at' => now(),
        ]);

        // Send notification to user (implement later)

        return back()->with('success', 'Advert approved successfully!');
    }

    public function reject(Advert $advert, Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $advert->update([
            'status' => 'rejected',
        ]);

        // Send notification to user with reason (implement later)

        return back()->with('success', 'Advert rejected!');
    }

    public function destroy(Advert $advert)
    {
        $advert->delete();
        return redirect()->route('admin.adverts.index')
            ->with('success', 'Advert deleted successfully!');
    }
}
