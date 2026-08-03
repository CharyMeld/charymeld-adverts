# User Networking Feature Implementation Guide

## Overview
This document outlines the implementation of user networking features for Charymeld Adverts, including:
- User profiles (create/edit)
- Follow/unfollow system
- Groups (create/join)
- Group chat
- Real-time 1-on-1 chat (WebSockets)
- Video upload with thumbnail generation (ffmpeg)
- Online presence tracking

## Database Schema

### Completed Migrations

#### 1. `user_profiles` Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `bio` - Text field (500 chars max)
- `website` - URL
- `location` - String
- `birth_date` - Date
- `cover_image` - String (file path)
- `social_links` - JSON (Facebook, Twitter, Instagram, LinkedIn)
- `occupation` - String
- `company` - String
- `privacy` - Enum (public, private, friends)
- `timestamps`

#### 2. `follows` Table
- `id` - Primary key
- `follower_id` - Foreign key to users (who is following)
- `following_id` - Foreign key to users (who is being followed)
- `timestamps`
- Unique constraint on (follower_id, following_id)

#### 3. `groups` Table
- `id` - Primary key
- `name` - String
- `slug` - String (unique, auto-generated)
- `description` - Text
- `cover_image` - String (file path)
- `created_by` - Foreign key to users
- `privacy` - Enum (public, private)
- `is_active` - Boolean
- `timestamps`

#### 4. `group_members` Table
- `id` - Primary key
- `group_id` - Foreign key to groups
- `user_id` - Foreign key to users
- `role` - Enum (admin, moderator, member)
- `timestamps`
- Unique constraint on (group_id, user_id)

#### 5. `group_messages` Table
- `id` - Primary key
- `group_id` - Foreign key to groups
- `user_id` - Foreign key to users
- `message` - Text
- `attachment` - String (file path, optional)
- `timestamps`

#### 6. `direct_messages` Table
- `id` - Primary key
- `sender_id` - Foreign key to users
- `receiver_id` - Foreign key to users
- `message` - Text
- `attachment` - String (file path, optional)
- `is_read` - Boolean
- `timestamps`

#### 7. `videos` Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `title` - String
- `description` - Text
- `video_path` - String (storage path)
- `thumbnail_path` - String (auto-generated via ffmpeg)
- `duration` - Integer (seconds)
- `file_size` - BigInteger (bytes)
- `mime_type` - String (default: video/mp4)
- `views` - Integer
- `privacy` - Enum (public, private, unlisted)
- `timestamps`

## Models Created

### 1. UserProfile Model
**Location:** `app/Models/UserProfile.php`
**Features:**
- Belongs to User
- JSON casting for social_links
- Date casting for birth_date

### 2. Follow Model
**Location:** `app/Models/Follow.php`
**Features:**
- Belongs to User (follower)
- Belongs to User (following)

### 3. Group Model
**Location:** `app/Models/Group.php`
**Features:**
- Auto-generates slug from name
- Belongs to User (creator)
- Many-to-many with Users (members) with role pivot
- Has many GroupMessages
- Helper methods: `isMember()`, `isAdminOrModerator()`

### 4. GroupMember Model
**Location:** `app/Models/GroupMember.php`
**Features:**
- Pivot model for group membership
- Belongs to Group and User

### 5. GroupMessage Model
**Location:** `app/Models/GroupMessage.php`
**Features:**
- Belongs to Group and User
- Supports text and file attachments

### 6. DirectMessage Model
**Location:** `app/Models/DirectMessage.php`
**Features:**
- Belongs to User (sender and receiver)
- Read tracking with `is_read` boolean
- Supports text and file attachments

### 7. Video Model
**Location:** `app/Models/Video.php`
**Features:**
- Belongs to User
- Tracks views, duration, file size
- Privacy settings
- Helper method: `incrementViews()`

## Updated User Model

**Location:** `app/Models/User.php`

Added relationships:
- `profile()` - Has one UserProfile
- `followers()` - Many-to-many Users (who follow this user)
- `following()` - Many-to-many Users (who this user follows)
- `groups()` - Many-to-many Groups with role pivot
- `createdGroups()` - Has many Groups (as creator)
- `videos()` - Has many Videos
- `sentDirectMessages()` - Has many DirectMessages
- `receivedDirectMessages()` - Has many DirectMessages
- `groupMessages()` - Has many GroupMessages

Added helper methods:
- `isFollowing($userId)` - Check if user follows another user
- `follow($userId)` - Follow a user
- `unfollow($userId)` - Unfollow a user

## Controllers Created

The following controller files have been created and need to be implemented:

### 1. ProfileController
**Location:** `app/Http/Controllers/ProfileController.php`
**Required Methods:**
```php
public function show($username)          // View user profile
public function edit()                    // Show edit form (own profile)
public function update(Request $request)  // Update profile
public function create()                  // Create profile (first time)
public function store(Request $request)   // Store new profile
```

**Validation Rules:**
- bio: max:500
- website: url|nullable
- birth_date: date|before:today
- cover_image: image|max:2048
- social_links: array with valid URLs

### 2. FollowController
**Location:** `app/Http/Controllers/FollowController.php`
**Required Methods:**
```php
public function follow($userId)          // Follow a user
public function unfollow($userId)        // Unfollow a user
public function followers($userId)       // Get user's followers list
public function following($userId)       // Get user's following list
```

### 3. GroupController
**Location:** `app/Http/Controllers/GroupController.php`
**Required Methods:**
```php
public function index()                  // List all public groups
public function create()                 // Show create group form
public function store(Request $request)  // Create new group
public function show($slug)              // Show group details
public function edit($slug)              // Edit group (admin only)
public function update(Request $request, $slug)  // Update group
public function join($slug)              // Join group
public function leave($slug)             // Leave group
public function members($slug)           // List group members
```

### 4. GroupChatController
**Location:** `app/Http/Controllers/GroupChatController.php`
**Required Methods:**
```php
public function index($groupId)          // Show group chat interface
public function sendMessage(Request $request, $groupId)  // Send message
public function getMessages($groupId)    // Get messages (paginated)
public function uploadAttachment(Request $request, $groupId)  // Upload file
```

**WebSocket Integration:**
- Broadcast new messages to `group.{groupId}` channel
- Use Laravel Reverb for real-time updates

### 5. DirectChatController
**Location:** `app/Http/Controllers/DirectChatController.php`
**Required Methods:**
```php
public function index()                  // List all conversations
public function show($userId)            // Show chat with specific user
public function sendMessage(Request $request, $userId)  // Send message
public function getMessages($userId)     // Get messages (paginated)
public function markAsRead($userId)      // Mark messages as read
public function uploadAttachment(Request $request, $userId)  // Upload file
```

**WebSocket Integration:**
- Broadcast to private channel `user.{userId}`
- Real-time message delivery
- Typing indicators

### 6. VideoController
**Location:** `app/Http/Controllers/VideoController.php`
**Required Methods:**
```php
public function index()                  // List all videos
public function create()                 // Show upload form
public function store(Request $request)  // Handle upload + ffmpeg processing
public function show($id)                // Show video player
public function stream($id)              // Stream video file
public function edit($id)                // Edit video details
public function update(Request $request, $id)  // Update video
public function destroy($id)             // Delete video
```

**ffmpeg Integration:**
```php
// Generate thumbnail at 2 seconds
$ffmpeg = FFMpeg::create();
$video = $ffmpeg->open($videoPath);
$frame = $video->frame(TimeCode::fromSeconds(2));
$frame->save($thumbnailPath);

// Get video duration
$duration = $video->getStreams()->first()->get('duration');
```

## WebSocket Configuration

### 1. Laravel Reverb Setup

**Install Reverb (already installed via composer):**
```bash
php artisan reverb:install
```

**Configure `.env`:**
```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

**Start Reverb Server:**
```bash
php artisan reverb:start
```

### 2. Broadcasting Channels

**Location:** `routes/channels.php`

Add the following channels:

```php
// Private user channel for direct messages
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Group channel for group chat
Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    return \App\Models\Group::find($groupId)->isMember($user->id);
});

// Presence channel for online users
Broadcast::channel('online', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->avatar
    ];
});
```

### 3. WebSocket Events

Create broadcasting events:

```bash
php artisan make:event MessageSent
php artisan make:event GroupMessageSent
php artisan make:event UserOnline
php artisan make:event UserOffline
```

**Example: `app/Events/MessageSent.php`**
```php
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(DirectMessage $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->message->receiver_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
```

### 4. Client-Side JavaScript

**Install Laravel Echo and Pusher JS:**
```bash
npm install --save-dev laravel-echo pusher-js
```

**Configure Echo in `resources/js/bootstrap.js`:**
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

**Listen for messages:**
```javascript
// Direct messages
Echo.private(`user.${userId}`)
    .listen('.message.sent', (e) => {
        console.log('New message:', e.message);
        appendMessageToChat(e.message);
    });

// Group messages
Echo.private(`group.${groupId}`)
    .listen('.group.message.sent', (e) => {
        console.log('New group message:', e.message);
        appendMessageToGroupChat(e.message);
    });

// Online presence
Echo.join('online')
    .here((users) => {
        console.log('Online users:', users);
    })
    .joining((user) => {
        console.log(user.name + ' joined');
    })
    .leaving((user) {
        console.log(user.name + ' left');
    });
```

## Video Upload with FFmpeg

### 1. Install FFmpeg

**Ubuntu/Linux:**
```bash
sudo apt-get install ffmpeg
```

**Verify installation:**
```bash
ffmpeg -version
```

### 2. Install PHP FFmpeg Library

```bash
composer require php-ffmpeg/php-ffmpeg
```

### 3. VideoController Implementation

```php
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'video' => 'required|file|mimes:mp4,mov,avi|max:102400', // 100MB max
        'description' => 'nullable',
        'privacy' => 'in:public,private,unlisted'
    ]);

    $video = $request->file('video');
    $filename = uniqid() . '.' . $video->getClientOriginalExtension();
    $videoPath = $video->storeAs('videos', $filename, 'public');

    // Generate thumbnail using ffmpeg
    $ffmpeg = FFMpeg::create();
    $videoFile = $ffmpeg->open(storage_path('app/public/' . $videoPath));

    $thumbnailName = uniqid() . '.jpg';
    $thumbnailPath = storage_path('app/public/thumbnails/' . $thumbnailName);

    $frame = $videoFile->frame(TimeCode::fromSeconds(2));
    $frame->save($thumbnailPath);

    // Get video metadata
    $duration = $videoFile->getStreams()->first()->get('duration');
    $fileSize = $video->getSize();

    // Save to database
    $videoModel = Video::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'video_path' => $videoPath,
        'thumbnail_path' => 'thumbnails/' . $thumbnailName,
        'duration' => (int) $duration,
        'file_size' => $fileSize,
        'mime_type' => $video->getMimeType(),
        'privacy' => $request->privacy ?? 'public',
    ]);

    return redirect()->route('videos.show', $videoModel->id);
}

public function stream($id)
{
    $video = Video::findOrFail($id);

    // Check permissions
    if ($video->privacy === 'private' && $video->user_id !== auth()->id()) {
        abort(403);
    }

    $video->incrementViews();

    $path = storage_path('app/public/' . $video->video_path);

    return response()->file($path, [
        'Content-Type' => $video->mime_type,
        'Accept-Ranges' => 'bytes',
    ]);
}
```

## Routes Configuration

Add the following to `routes/web.php`:

```php
// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Follow routes
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('following');

    // Group routes
    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('index');
        Route::get('/create', [GroupController::class, 'create'])->name('create');
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::get('/{slug}', [GroupController::class, 'show'])->name('show');
        Route::get('/{slug}/edit', [GroupController::class, 'edit'])->name('edit');
        Route::put('/{slug}', [GroupController::class, 'update'])->name('update');
        Route::post('/{slug}/join', [GroupController::class, 'join'])->name('join');
        Route::delete('/{slug}/leave', [GroupController::class, 'leave'])->name('leave');
        Route::get('/{slug}/members', [GroupController::class, 'members'])->name('members');
    });

    // Group chat routes
    Route::prefix('groups/{group}/chat')->name('group-chat.')->group(function () {
        Route::get('/', [GroupChatController::class, 'index'])->name('index');
        Route::post('/messages', [GroupChatController::class, 'sendMessage'])->name('send');
        Route::get('/messages', [GroupChatController::class, 'getMessages'])->name('messages');
        Route::post('/attachment', [GroupChatController::class, 'uploadAttachment'])->name('attachment');
    });

    // Direct chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [DirectChatController::class, 'index'])->name('index');
        Route::get('/{user}', [DirectChatController::class, 'show'])->name('show');
        Route::post('/{user}/messages', [DirectChatController::class, 'sendMessage'])->name('send');
        Route::get('/{user}/messages', [DirectChatController::class, 'getMessages'])->name('messages');
        Route::post('/{user}/read', [DirectChatController::class, 'markAsRead'])->name('read');
        Route::post('/{user}/attachment', [DirectChatController::class, 'uploadAttachment'])->name('attachment');
    });

    // Video routes
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::get('/{video}', [VideoController::class, 'show'])->name('show');
        Route::get('/{video}/stream', [VideoController::class, 'stream'])->name('stream');
        Route::get('/{video}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{video}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{video}', [VideoController::class, 'destroy'])->name('destroy');
    });
});
```

## Storage Configuration

Create necessary storage directories:

```bash
php artisan storage:link
mkdir -p storage/app/public/videos
mkdir -p storage/app/public/thumbnails
mkdir -p storage/app/public/profiles/covers
mkdir -p storage/app/public/groups/covers
mkdir -p storage/app/public/chat/attachments
```

Set permissions:
```bash
chmod -R 775 storage/app/public
```

## Next Steps

1. **Run Migrations:**
   ```bash
   # Start MySQL first
   sudo /opt/lampp/lampp startmysql

   # Then run migrations
   php artisan migrate
   ```

2. **Implement Controllers:**
   - Complete each controller's methods with proper validation
   - Add rate limiting using middleware
   - Implement file upload handling
   - Add authorization checks

3. **Create Views:**
   - User profile page (view/edit)
   - Groups list and detail pages
   - Group chat interface
   - Direct chat interface
   - Video upload and player pages

4. **Setup WebSockets:**
   - Configure Reverb in .env
   - Create broadcasting events
   - Implement client-side Echo listeners

5. **Install FFmpeg:**
   ```bash
   sudo apt-get install ffmpeg
   composer require php-ffmpeg/php-ffmpeg
   ```

6. **Frontend Integration:**
   - Build chat UI components
   - Add real-time message updates
   - Implement online presence indicators
   - Create video player with HTML5 `<video>` tag

7. **Testing:**
   - Test follow/unfollow functionality
   - Test group creation and membership
   - Test real-time chat (both group and direct)
   - Test video upload and streaming
   - Test online presence tracking

## Security Considerations

1. **Authorization:**
   - Only profile owners can edit profiles
   - Only group admins can edit group settings
   - Private groups require membership to view
   - Private videos only viewable by owner

2. **File Uploads:**
   - Validate file types strictly
   - Limit file sizes (videos: 100MB, images: 2MB)
   - Sanitize filenames
   - Store outside web root when possible

3. **Rate Limiting:**
   - Limit message sending (e.g., 60/minute)
   - Limit follow/unfollow actions
   - Limit video uploads per day

4. **WebSocket Security:**
   - Use private channels for sensitive data
   - Verify user permissions in channel authorization
   - Validate all incoming data

## Database Indexes

Already added in migrations:
- `follows`: (follower_id, following_id), follower_id, following_id
- `groups`: slug, created_by
- `group_members`: (group_id, user_id), group_id, user_id
- `group_messages`: group_id, user_id, created_at
- `direct_messages`: (sender_id, receiver_id), created_at
- `videos`: user_id, created_at
- `user_profiles`: user_id

## Summary

All foundational elements have been created:
- ✅ 7 database migrations
- ✅ 7 models with relationships
- ✅ Updated User model
- ✅ 6 controller files created
- ✅ Reverb already installed (laravel/reverb in composer.json)

**Still needed:**
- Controller implementations
- Routes configuration
- View files
- WebSocket events
- Reverb configuration
- FFmpeg installation and integration
- Frontend JavaScript for real-time features

This provides a complete social networking layer on top of your advertising platform!
