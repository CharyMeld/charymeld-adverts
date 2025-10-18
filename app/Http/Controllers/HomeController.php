<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Category;
use App\Models\Blog;
use App\Models\AdvertAnalytic;
use App\Models\UserAdView;
use App\Services\RecommendationService;
use App\Helpers\SeoHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    	public function index(RecommendationService $recommendationService)
	{
	    $categories = Category::with(['children.adverts.primaryImage', 'adverts.primaryImage'])
		->whereNull('parent_id') // Only top-level categories
		->get();

	    foreach ($categories as $category) {
		$adverts = collect();

		// Parent category adverts
		$adverts = $adverts->merge(
		    $category->adverts()
		        ->active()
		        ->approved()
		        ->notExpired()
		        ->latest()
		        ->take(10)
		        ->get()
		);

		// Child category adverts
		foreach ($category->children as $child) {
		    $adverts = $adverts->merge(
		        $child->adverts()
		            ->active()
		            ->approved()
		            ->notExpired()
		            ->latest()
		            ->take(10)
		            ->get()
		    );
		}

		// Sort all merged adverts by creation date (latest first)
		$adverts = $adverts->sortByDesc('created_at')->take(15);

		// Attach the final collection to the category
		$category->setRelation('adverts', $adverts);
	    }

	    // Get personalized recommendations for logged-in users or based on session
	    $recommendedAds = $recommendationService->getHomepageRecommendations(
	        auth()->id(),
	        session()->getId(),
	        12
	    );

	    return view('home', compact('categories', 'recommendedAds'));
	}

    public function search(Request $request)
    {
        // If there's a search query, use Scout for better search
        if ($request->filled('q')) {
            $keyword = $request->q;

            // Use Scout search
            $query = Advert::search($keyword);

            // Apply filters to the Scout query
            $query->query(function ($builder) use ($request) {
                $builder->with(['category', 'primaryImage', 'user'])
                    ->active()
                    ->approved()
                    ->notExpired();

                // Filter by category
                if ($request->filled('category_id')) {
                    $builder->where('category_id', $request->category_id);
                }

                // Filter by price range
                if ($request->filled('min_price')) {
                    $builder->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $builder->where('price', '<=', $request->max_price);
                }
            });

            $adverts = $query->paginate(20)->withQueryString();
        } else {
            // If no search query, use traditional database query
            $query = Advert::with(['category', 'primaryImage', 'user'])
                ->active()
                ->approved()
                ->notExpired();

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
        }

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

        // Generate SEO meta
        $seo = SeoHelper::generateCategoryMeta($category);

        return view('adverts.category', compact('category', 'adverts', 'seo'));
    }

   	public function show($slug, RecommendationService $recommendationService)
	{
	    $advert = Advert::with(['category', 'images', 'user'])
		->where('slug', $slug)
		->active()
		->approved()
		->notExpired()
		->firstOrFail();

	    // Increment view count
	    $advert->incrementViews();

	    // Record impression and click for analytics
	    AdvertAnalytic::recordImpression($advert->id);
	    AdvertAnalytic::recordClick($advert->id);

	    // Track user view for recommendations
	    UserAdView::recordView(
	        $advert->id,
	        auth()->id(),
	        session()->getId(),
	        $advert->category_id
	    );

	    // Get AI-powered similar adverts
	    $similarAdverts = $recommendationService->getSimilarAds($advert, 6);

	    // Get related blog posts
	    $relatedBlogs = $recommendationService->getRelatedBlogPosts($advert, 3);

	    // Generate SEO meta
	    $seo = SeoHelper::generateAdvertMeta($advert);

	    return view('adverts.show', compact('advert', 'similarAdverts', 'relatedBlogs', 'seo'));
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

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function dataDeletion()
    {
        return view('pages.data-deletion');
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
