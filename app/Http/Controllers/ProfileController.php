<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $user->load(['profile', 'followers', 'following', 'videos', 'groups']);

        $isOwnProfile = auth()->check() && auth()->id() === $user->id;
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user->id) : false;

        return view('profile.show', compact('user', 'isOwnProfile', 'isFollowing'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Create new profile
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'privacy' => 'in:public,private,friends',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        // Update user name if provided
        $user = auth()->user();
        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        $data = [
            'user_id' => auth()->id(),
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
            'location' => $validated['location'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'company' => $validated['company'] ?? null,
            'privacy' => $validated['privacy'] ?? 'public',
        ];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('profiles/covers', 'public');
            $data['cover_image'] = $path;
        }

        // Build social links array
        $socialLinks = [];
        foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $platform) {
            if (!empty($validated[$platform])) {
                $socialLinks[$platform] = $validated[$platform];
            }
        }
        $data['social_links'] = !empty($socialLinks) ? $socialLinks : null;

        UserProfile::create($data);

        return redirect()->route('profile.show', auth()->user()->name)
            ->with('success', 'Profile created successfully!');
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('profile.store');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'occupation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'privacy' => 'in:public,private,friends',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        // Update user name if provided
        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        $data = [
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
            'location' => $validated['location'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'company' => $validated['company'] ?? null,
            'privacy' => $validated['privacy'] ?? 'public',
        ];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($profile->cover_image) {
                Storage::disk('public')->delete($profile->cover_image);
            }
            $path = $request->file('cover_image')->store('profiles/covers', 'public');
            $data['cover_image'] = $path;
        }

        // Build social links array
        $socialLinks = [];
        foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $platform) {
            if (!empty($validated[$platform])) {
                $socialLinks[$platform] = $validated[$platform];
            }
        }
        $data['social_links'] = !empty($socialLinks) ? $socialLinks : null;

        $profile->update($data);

        return redirect()->route('profile.show', $user->name)
            ->with('success', 'Profile updated successfully!');
    }
}
