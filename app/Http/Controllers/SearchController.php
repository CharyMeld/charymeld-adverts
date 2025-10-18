<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\SearchQuery;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'all'); // all, adverts, blogs, categories, users

        if (empty($query)) {
            return view('search.index', [
                'query' => '',
                'results' => collect(),
                'type' => $type,
            ]);
        }

        $results = collect();

        switch ($type) {
            case 'adverts':
                $results = $this->searchAdverts($query);
                break;
            case 'blogs':
                $results = $this->searchBlogs($query);
                break;
            case 'categories':
                $results = $this->searchCategories($query);
                break;
            case 'users':
                $results = $this->searchUsers($query);
                break;
            default:
                $results = $this->searchAll($query);
                break;
        }

        // Track search query
        SearchQuery::create([
            'query' => $query,
            'results_count' => is_countable($results) ? count($results) : 0,
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
        ]);

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'type' => $type,
        ]);
    }

    /**
     * Search adverts only
     */
    private function searchAdverts($query)
    {
        return Advert::search($query)
            ->query(fn ($builder) => $builder->where('status', 'approved')->where('is_active', true))
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * Search blogs only
     */
    private function searchBlogs($query)
    {
        return Blog::search($query)
            ->query(fn ($builder) => $builder->where('is_published', true))
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * Search categories only
     */
    private function searchCategories($query)
    {
        return Category::search($query)
            ->query(fn ($builder) => $builder->where('is_active', true))
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * Search users only
     */
    private function searchUsers($query)
    {
        return User::search($query)
            ->query(fn ($builder) => $builder->where('is_active', true))
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * Search across all models
     */
    private function searchAll($query)
    {
        $adverts = Advert::search($query)
            ->query(fn ($builder) => $builder->where('status', 'approved')->where('is_active', true))
            ->take(10)
            ->get()
            ->map(fn ($item) => ['type' => 'advert', 'data' => $item]);

        $blogs = Blog::search($query)
            ->query(fn ($builder) => $builder->where('is_published', true))
            ->take(10)
            ->get()
            ->map(fn ($item) => ['type' => 'blog', 'data' => $item]);

        $categories = Category::search($query)
            ->query(fn ($builder) => $builder->where('is_active', true))
            ->take(10)
            ->get()
            ->map(fn ($item) => ['type' => 'category', 'data' => $item]);

        $users = User::search($query)
            ->query(fn ($builder) => $builder->where('is_active', true))
            ->take(10)
            ->get()
            ->map(fn ($item) => ['type' => 'user', 'data' => $item]);

        return $adverts->concat($blogs)->concat($categories)->concat($users);
    }

    /**
     * API endpoint for instant search (AJAX)
     */
    public function instant(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // Search adverts
        try {
            $adverts = Advert::search($query)
                ->query(fn ($builder) => $builder->where('status', 'approved')->where('is_active', true))
                ->take(5)
                ->get()
                ->map(fn ($item) => [
                    'type' => 'advert',
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => route('advert.show', $item->slug),
                    'image' => $item->primaryImage?->image_path ?? null,
                ]);
            $results = $results->concat($adverts);
        } catch (\Exception $e) {
            // Skip if index doesn't exist
        }

        // Search blogs
        try {
            $blogs = Blog::search($query)
                ->query(fn ($builder) => $builder->where('is_published', true))
                ->take(3)
                ->get()
                ->map(fn ($item) => [
                    'type' => 'blog',
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => route('blog.show', $item->slug),
                ]);
            $results = $results->concat($blogs);
        } catch (\Exception $e) {
            // Skip if index doesn't exist
        }

        // Search categories
        try {
            $categories = Category::search($query)
                ->query(fn ($builder) => $builder->where('is_active', true))
                ->take(3)
                ->get()
                ->map(fn ($item) => [
                    'type' => 'category',
                    'id' => $item->id,
                    'title' => $item->name,
                    'url' => route('category', $item->slug),
                ]);
            $results = $results->concat($categories);
        } catch (\Exception $e) {
            // Skip if index doesn't exist
        }

        return response()->json([
            'results' => $results
        ]);
    }
}
