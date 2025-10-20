<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * List all public groups
     */
    public function index()
    {
        $groups = Group::where('is_active', true)
            ->where('privacy', 'public')
            ->withCount('members')
            ->latest()
            ->paginate(12);

        $myGroups = auth()->check() ? auth()->user()->groups : collect();

        return view('groups.index', compact('groups', 'myGroups'));
    }

    /**
     * Show create group form
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Create new group
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:1000',
            'privacy' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'privacy' => $validated['privacy'],
            'created_by' => auth()->id(),
            'is_active' => true,
        ];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('groups/covers', 'public');
            $data['cover_image'] = $path;
        }

        $group = Group::create($data);

        // Add creator as admin member
        $group->members()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'Group created successfully!');
    }

    /**
     * Show group details
     */
    public function show($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        $group->load(['creator', 'members']);

        // Check if private and user is not a member
        if ($group->privacy === 'private' && !$group->isMember(auth()->id())) {
            abort(403, 'This group is private.');
        }

        $isMember = auth()->check() ? $group->isMember(auth()->id()) : false;
        $isAdmin = auth()->check() ? $group->isAdminOrModerator(auth()->id()) : false;
        $recentMessages = $isMember ? $group->messages()->with('user')->latest()->take(10)->get() : collect();

        return view('groups.show', compact('group', 'isMember', 'isAdmin', 'recentMessages'));
    }

    /**
     * Show edit group form
     */
    public function edit($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Only admin can edit
        if (!$group->isAdminOrModerator(auth()->id())) {
            abort(403, 'Only group admins can edit this group.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Update group
     */
    public function update(Request $request, $slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Only admin can update
        if (!$group->isAdminOrModerator(auth()->id())) {
            abort(403, 'Only group admins can edit this group.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string|max:1000',
            'privacy' => 'required|in:public,private',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'privacy' => $validated['privacy'],
            'is_active' => $validated['is_active'] ?? true,
        ];

        // Update slug if name changed
        if ($validated['name'] !== $group->name) {
            $data['slug'] = Str::slug($validated['name']);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($group->cover_image) {
                Storage::disk('public')->delete($group->cover_image);
            }
            $path = $request->file('cover_image')->store('groups/covers', 'public');
            $data['cover_image'] = $path;
        }

        $group->update($data);

        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'Group updated successfully!');
    }

    /**
     * Join a group
     */
    public function join($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        if (!$group->is_active) {
            return back()->with('error', 'This group is not active.');
        }

        if ($group->isMember(auth()->id())) {
            return back()->with('error', 'You are already a member of this group.');
        }

        if ($group->privacy === 'private') {
            return back()->with('error', 'This is a private group. You need an invitation to join.');
        }

        $group->members()->attach(auth()->id(), ['role' => 'member']);

        return back()->with('success', "You have joined {$group->name}!");
    }

    /**
     * Leave a group
     */
    public function leave($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        if (!$group->isMember(auth()->id())) {
            return back()->with('error', 'You are not a member of this group.');
        }

        // Check if user is the only admin
        $member = $group->members()->where('user_id', auth()->id())->first();
        if ($member->pivot->role === 'admin') {
            $adminCount = $group->members()->wherePivot('role', 'admin')->count();
            if ($adminCount === 1) {
                return back()->with('error', 'You cannot leave as you are the only admin. Please assign another admin first.');
            }
        }

        $group->members()->detach(auth()->id());

        return redirect()->route('groups.index')
            ->with('success', "You have left {$group->name}.");
    }

    /**
     * Get group members
     */
    public function members($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        // Check access
        if ($group->privacy === 'private' && !$group->isMember(auth()->id())) {
            abort(403, 'This group is private.');
        }

        $members = $group->members()->withPivot('role')->paginate(20);

        return view('groups.members', compact('group', 'members'));
    }
}
