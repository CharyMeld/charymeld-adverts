<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Blog;
use App\Models\Advert;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FeedController extends Controller
{
    /**
     * Display the unified feed
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, videos, blogs, ads
        $perPage = 20;

        // Get all content types with their timestamps
        $items = collect();

        // Get videos
        if (in_array($type, ['all', 'videos'])) {
            $videos = Video::with(['user', 'reactions'])
                ->where('privacy', 'public')
                ->get()
                ->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'type' => 'video',
                        'title' => $video->title,
                        'description' => $video->description,
                        'content' => $video->description,
                        'image' => $video->thumbnail_path ? asset('storage/' . $video->thumbnail_path) : null,
                        'video_path' => route('videos.stream', $video->id),
                        'url' => route('videos.show', $video->id),
                        'user' => $video->user,
                        'user_name' => $video->user->name,
                        'user_avatar' => $video->user->avatar ? asset('storage/' . $video->user->avatar) : asset('images/default-avatar.png'),
                        'views' => $video->views,
                        'reactions' => $video->reactions,
                        'reaction_counts' => $video->getReactionCounts(),
                        'created_at' => $video->created_at,
                        'time_ago' => $video->created_at->diffForHumans(),
                        'model' => $video,
                    ];
                });
            $items = $items->merge($videos);
        }

        // Get blogs
        if (in_array($type, ['all', 'blogs'])) {
            $blogs = Blog::with(['user', 'category'])
                ->where('is_published', true)
                ->get()
                ->map(function ($blog) {
                    return [
                        'id' => $blog->id,
                        'type' => 'blog',
                        'title' => $blog->title,
                        'description' => $blog->excerpt,
                        'content' => strip_tags(substr($blog->content, 0, 200)) . '...',
                        'image' => $blog->featured_image ? asset('storage/' . $blog->featured_image) : null,
                        'url' => route('blog.show', $blog->slug),
                        'user' => $blog->user,
                        'user_name' => $blog->user->name,
                        'user_avatar' => $blog->user->avatar ? asset('storage/' . $blog->user->avatar) : asset('images/default-avatar.png'),
                        'category' => $blog->category->name ?? null,
                        'views' => $blog->views ?? 0,
                        'created_at' => $blog->created_at,
                        'time_ago' => $blog->created_at->diffForHumans(),
                        'model' => $blog,
                    ];
                });
            $items = $items->merge($blogs);
        }

        // Get ads (only approved and active)
        if (in_array($type, ['all', 'ads'])) {
            $ads = Advert::with(['user', 'category'])
                ->where('status', 'approved')
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->get()
                ->map(function ($ad) {
                    $images = json_decode($ad->images, true) ?? [];
                    return [
                        'id' => $ad->id,
                        'type' => 'ad',
                        'title' => $ad->title,
                        'description' => $ad->description,
                        'content' => substr($ad->description, 0, 200) . '...',
                        'image' => !empty($images) ? asset('storage/' . $images[0]) : null,
                        'url' => route('advert.show', $ad->slug),
                        'user' => $ad->user,
                        'user_name' => $ad->user->name,
                        'user_avatar' => $ad->user->avatar ? asset('storage/' . $ad->user->avatar) : asset('images/default-avatar.png'),
                        'category' => $ad->category->name ?? null,
                        'price' => $ad->price,
                        'location' => $ad->location,
                        'views' => $ad->views ?? 0,
                        'created_at' => $ad->created_at,
                        'time_ago' => $ad->created_at->diffForHumans(),
                        'model' => $ad,
                    ];
                });
            $items = $items->merge($ads);
        }

        // Sort by created_at (most recent first)
        $items = $items->sortByDesc('created_at');

        // Paginate manually
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values();

        $total = $items->count();
        $lastPage = ceil($total / $perPage);

        // Always load categories (needed for home page hero section)
        $categories = Category::all();

        return view('feed.index', [
            'items' => $paginatedItems,
            'currentPage' => $currentPage,
            'lastPage' => $lastPage,
            'total' => $total,
            'type' => $type,
            'categories' => $categories,
        ]);
    }

    /**
     * Handle reactions on feed items
     */
    public function react(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,love,laugh,wow,sad,angry',
            'item_type' => 'required|in:video,blog,ad',
            'item_id' => 'required|integer'
        ]);

        $modelClass = match($validated['item_type']) {
            'video' => Video::class,
            'blog' => Blog::class,
            'ad' => Advert::class,
        };

        $item = $modelClass::findOrFail($validated['item_id']);

        // Check if user already reacted
        $existingReaction = $item->reactions()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReaction) {
            // If same reaction, remove it (toggle)
            if ($existingReaction->type === $validated['type']) {
                $existingReaction->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Reaction removed',
                    'user_reaction' => null
                ]);
            }
            // Otherwise, update to new reaction
            $existingReaction->update(['type' => $validated['type']]);
        } else {
            // Create new reaction
            $item->reactions()->create([
                'user_id' => auth()->id(),
                'type' => $validated['type']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reaction saved',
            'user_reaction' => $validated['type']
        ]);
    }

    /**
     * Post a comment on a feed item
     */
    public function comment(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:video,blog,ad',
            'item_id' => 'required|integer',
            'comment' => 'required|string|max:1000'
        ]);

        $modelClass = match($validated['item_type']) {
            'video' => Video::class,
            'blog' => Blog::class,
            'ad' => Advert::class,
        };

        $item = $modelClass::findOrFail($validated['item_id']);

        $comment = $item->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $validated['comment']
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => auth()->user()->name,
                'user_avatar' => auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null,
                'comment' => $comment->comment,
                'time_ago' => $comment->created_at->diffForHumans()
            ]
        ]);
    }

    /**
     * Get comments for a feed item
     */
    public function getComments($type, $id)
    {
        $modelClass = match($type) {
            'video' => Video::class,
            'blog' => Blog::class,
            'ad' => Advert::class,
            default => abort(400, 'Invalid type')
        };

        $item = $modelClass::findOrFail($id);

        $comments = $item->comments()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : null,
                    'comment' => $comment->comment,
                    'time_ago' => $comment->created_at->diffForHumans()
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
