<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')
            ->ordered()
            ->get();

        $stats = [
            'total' => BlogCategory::count(),
            'active' => BlogCategory::where('is_active', true)->count(),
            'inactive' => BlogCategory::where('is_active', false)->count(),
        ];

        return view('admin.blog-categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.blog-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category created successfully!');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $blogCategory->update($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category updated successfully!');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        // Check if category has blogs
        if ($blogCategory->blogs()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing blog posts. Please reassign or delete the posts first.');
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category deleted successfully!');
    }

    public function toggleStatus(BlogCategory $blogCategory)
    {
        $blogCategory->update([
            'is_active' => !$blogCategory->is_active
        ]);

        return back()->with('success', 'Category status updated successfully!');
    }
}
