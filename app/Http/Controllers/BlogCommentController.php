<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255',
        ]);

        $commentData = [
            'blog_id' => $blog->id,
            'comment' => $validated['comment'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => 'pending', // All comments require moderation
        ];

        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            $commentData['name'] = $validated['name'];
            $commentData['email'] = $validated['email'];
        }

        BlogComment::create($commentData);

        return back()->with('success', 'Your comment has been submitted and is pending approval.');
    }

    // Admin methods
    public function index()
    {
        $this->authorize('admin');

        $comments = BlogComment::with(['blog', 'user'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => BlogComment::count(),
            'pending' => BlogComment::where('status', 'pending')->count(),
            'approved' => BlogComment::where('status', 'approved')->count(),
            'spam' => BlogComment::where('status', 'spam')->count(),
        ];

        return view('admin.blog-comments.index', compact('comments', 'stats'));
    }

    public function approve(BlogComment $comment)
    {
        $this->authorize('admin');

        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Comment approved successfully!');
    }

    public function reject(BlogComment $comment)
    {
        $this->authorize('admin');

        $comment->update(['status' => 'spam']);

        return back()->with('success', 'Comment marked as spam!');
    }

    public function destroy(BlogComment $comment)
    {
        $this->authorize('admin');

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
