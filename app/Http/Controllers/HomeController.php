<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredAdverts = Advert::with(['category', 'primaryImage'])
            ->active()
            ->approved()
            ->featured()
            ->notExpired()
            ->latest()
            ->take(8)
            ->get();

        $premiumAdverts = Advert::with(['category', 'primaryImage'])
            ->active()
            ->approved()
            ->premium()
            ->notExpired()
            ->latest()
            ->take(8)
            ->get();

        $latestAdverts = Advert::with(['category', 'primaryImage'])
            ->active()
            ->approved()
            ->notExpired()
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::parents()
            ->active()
            ->with(['children' => function($query) {
                $query->active()->withCount('adverts');
            }])
            ->withCount('adverts')
            ->get();

        $latestBlogs = Blog::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredAdverts',
            'premiumAdverts',
            'latestAdverts',
            'categories',
            'latestBlogs'
        ));
    }

    public function search(Request $request)
    {
        $query = Advert::with(['category', 'primaryImage', 'user'])
            ->active()
            ->approved()
            ->notExpired();

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        $adverts = $query->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('adverts.search', compact('adverts', 'categories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Advert::with(['category', 'primaryImage', 'user'])
            ->active()
            ->approved()
            ->notExpired();

        // Include category and its children
        $categoryIds = [$category->id];
        if ($category->children()->exists()) {
            $categoryIds = array_merge($categoryIds, $category->children()->pluck('id')->toArray());
        }

        $query->whereIn('category_id', $categoryIds);

        $adverts = $query->latest()->paginate(20);

        return view('adverts.category', compact('category', 'adverts'));
    }

    public function show($slug)
    {
        $advert = Advert::with(['category', 'images', 'user'])
            ->where('slug', $slug)
            ->active()
            ->approved()
            ->notExpired()
            ->firstOrFail();

        // Increment view count
        $advert->incrementViews();

        // Get related adverts
        $relatedAdverts = Advert::with(['category', 'primaryImage'])
            ->where('category_id', $advert->category_id)
            ->where('id', '!=', $advert->id)
            ->active()
            ->approved()
            ->notExpired()
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('adverts.show', compact('advert', 'relatedAdverts'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email to admin (implement email notification later)
        // For now, just return success

        return back()->with('success', 'Thank you for contacting us. We will get back to you soon!');
    }
}
