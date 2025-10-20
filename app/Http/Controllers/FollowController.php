<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Follow a user
     */
    public function follow($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        auth()->user()->follow($userId);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "You are now following {$user->name}",
                'followers_count' => $user->followers()->count()
            ]);
        }

        return back()->with('success', "You are now following {$user->name}");
    }

    /**
     * Unfollow a user
     */
    public function unfollow($userId)
    {
        $user = User::findOrFail($userId);

        auth()->user()->unfollow($userId);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "You have unfollowed {$user->name}",
                'followers_count' => $user->followers()->count()
            ]);
        }

        return back()->with('success', "You have unfollowed {$user->name}");
    }

    /**
     * Get user's followers
     */
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->paginate(20);

        return view('profile.followers', compact('user', 'followers'));
    }

    /**
     * Get user's following
     */
    public function following($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->following()->paginate(20);

        return view('profile.following', compact('user', 'following'));
    }
}
