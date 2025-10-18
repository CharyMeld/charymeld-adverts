<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Helpers\SeoHelper;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::published()
            ->with(['user', 'category'])
            ->latest('published_at')
            ->paginate(12);

        return view('blogs.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->published()
            ->with(['user', 'category', 'approvedComments.user', 'approvedComments.replies'])
            ->firstOrFail();

        // Increment view count
        $blog->incrementViews();

        // Get related blogs (prioritize same category)
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category_id, function ($query) use ($blog) {
                $query->where('category_id', $blog->category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // Generate SEO meta
        $seo = SeoHelper::generateBlogMeta($blog);

        return view('blogs.show', compact('blog', 'relatedBlogs', 'seo'));
    }

    public function category($slug)
    {
        $category = \App\Models\BlogCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $blogs = Blog::published()
            ->where('category_id', $category->id)
            ->with(['user', 'category'])
            ->latest('published_at')
            ->paginate(12);

        return view('blogs.category', compact('blogs', 'category'));
    }
}
