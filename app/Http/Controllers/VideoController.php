<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * List all videos
     */
    public function index()
    {
        $videos = Video::where('privacy', 'public')
            ->orWhere(function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('user')
            ->latest()
            ->paginate(12);

        return view('videos.index', compact('videos'));
    }

    /**
     * Show upload form
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Handle video upload and ffmpeg processing
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:102400', // 100MB max
            'privacy' => 'required|in:public,private,unlisted',
        ]);

        $videoFile = $request->file('video');
        $filename = uniqid() . '.' . $videoFile->getClientOriginalExtension();
        $videoPath = $videoFile->storeAs('videos', $filename, 'public');

        // Generate thumbnail using ffmpeg
        $thumbnailPath = null;
        $duration = null;

        try {
            $ffmpeg = \FFMpeg\FFMpeg::create();
            $video = $ffmpeg->open(storage_path('app/public/' . $videoPath));

            $thumbnailName = uniqid() . '.jpg';
            $thumbnailFullPath = storage_path('app/public/thumbnails/' . $thumbnailName);

            // Ensure thumbnails directory exists
            if (!file_exists(storage_path('app/public/thumbnails'))) {
                mkdir(storage_path('app/public/thumbnails'), 0755, true);
            }

            // Generate thumbnail at 2 seconds
            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(2));
            $frame->save($thumbnailFullPath);

            $thumbnailPath = 'thumbnails/' . $thumbnailName;

            // Get video duration
            $duration = (int) $video->getStreams()->first()->get('duration');
        } catch (\Exception $e) {
            // Log error and continue without thumbnail
            \Log::error('FFmpeg error: ' . $e->getMessage());
        }

        $videoModel = Video::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'duration' => $duration,
            'file_size' => $videoFile->getSize(),
            'mime_type' => $videoFile->getMimeType(),
            'privacy' => $validated['privacy'],
        ]);

        return redirect()->route('videos.show', $videoModel->id)
            ->with('success', 'Video uploaded successfully!');
    }

    /**
     * Show video player
     */
    public function show($id)
    {
        $video = Video::with('user')->findOrFail($id);

        // Check permissions
        if ($video->privacy === 'private' && $video->user_id !== auth()->id()) {
            abort(403, 'This video is private.');
        }

        // Increment view count
        $video->incrementViews();

        // Get related videos
        $relatedVideos = Video::where('user_id', $video->user_id)
            ->where('id', '!=', $video->id)
            ->where('privacy', 'public')
            ->take(6)
            ->get();

        return view('videos.show', compact('video', 'relatedVideos'));
    }

    /**
     * Stream video file
     */
    public function stream($id)
    {
        $video = Video::findOrFail($id);

        // Check permissions
        if ($video->privacy === 'private' && $video->user_id !== auth()->id()) {
            abort(403);
        }

        $path = storage_path('app/public/' . $video->video_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $video->mime_type,
            'Accept-Ranges' => 'bytes',
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);

        // Check ownership
        if ($video->user_id !== auth()->id()) {
            abort(403, 'You can only edit your own videos.');
        }

        return view('videos.edit', compact('video'));
    }

    /**
     * Update video details
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        // Check ownership
        if ($video->user_id !== auth()->id()) {
            abort(403, 'You can only edit your own videos.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'privacy' => 'required|in:public,private,unlisted',
        ]);

        $video->update($validated);

        return redirect()->route('videos.show', $video->id)
            ->with('success', 'Video updated successfully!');
    }

    /**
     * Delete video
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        // Check ownership
        if ($video->user_id !== auth()->id()) {
            abort(403, 'You can only delete your own videos.');
        }

        // Delete video file
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }

        // Delete thumbnail
        if ($video->thumbnail_path) {
            Storage::disk('public')->delete($video->thumbnail_path);
        }

        $video->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Video deleted successfully!');
    }
}
