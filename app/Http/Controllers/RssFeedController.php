<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    public function blog()
    {
        $blogs = Blog::with(['user', 'category'])
            ->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()->view('rss.blog', compact('blogs'))
            ->header('Content-Type', 'application/rss+xml');
    }

    public function category($slug)
    {
        $category = \App\Models\BlogCategory::where('slug', $slug)->firstOrFail();

        $blogs = Blog::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()->view('rss.blog', compact('blogs', 'category'))
            ->header('Content-Type', 'application/rss+xml');
    }
}
