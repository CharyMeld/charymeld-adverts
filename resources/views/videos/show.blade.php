@extends('layouts.app')

@section('content')
<script>
function handleReaction(type, videoId) {
    fetch('{{ route("reactions.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reactable_type: 'video',
            reactable_id: videoId,
            type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all reaction counts
            const reactionTypes = ['like', 'love', 'laugh', 'wow', 'sad', 'angry'];
            reactionTypes.forEach(rType => {
                const countEl = document.querySelector('.reaction-count-' + rType);
                if (countEl) {
                    countEl.textContent = data.reaction_counts[rType] || 0;
                }

                // Update button styles
                const btn = document.getElementById('reaction-' + rType);
                if (btn) {
                    if (data.message === 'Reaction removed') {
                        // Remove active state from all buttons
                        btn.className = 'reaction-btn px-3 py-2 rounded-lg border transition bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600';
                    } else if (rType === type) {
                        // Add active state to clicked button
                        btn.className = 'reaction-btn px-3 py-2 rounded-lg border transition bg-blue-100 dark:bg-blue-900 border-blue-500';
                    } else {
                        // Remove active state from other buttons
                        btn.className = 'reaction-btn px-3 py-2 rounded-lg border transition bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600';
                    }
                }
            });
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Video Player -->
        <div class="lg:col-span-2">
            <!-- Auto-play Controls -->
            <div class="bg-white dark:bg-gray-800 rounded-t-lg px-4 py-2 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="autoplayToggle" class="w-4 h-4 rounded" checked>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Auto-play</span>
                    </label>
                    <span id="upNextTimer" class="text-sm text-gray-500 dark:text-gray-400 hidden">
                        Next video in <span id="countdown">5</span>s
                    </span>
                </div>
            </div>

            <div class="bg-black overflow-hidden relative">
                <video id="videoPlayer" controls autoplay muted playsinline class="w-full" poster="{{ $video->thumbnail_path ? asset('storage/' . $video->thumbnail_path) : '' }}">
                    <source src="{{ route('videos.stream', $video->id) }}" type="{{ $video->mime_type }}">
                    Your browser does not support the video tag.
                </video>
                <!-- Unmute overlay -->
                <div id="unmuteOverlay" class="hidden absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center cursor-pointer">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-white text-lg font-semibold">Click to unmute and play</p>
                    </div>
                </div>
            </div>

            <!-- Video Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mt-4">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $video->title }}</h1>
                    @auth
                        @if($video->user_id === auth()->id())
                            <div class="flex gap-2">
                                <a href="{{ route('videos.edit', $video->id) }}" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('profile.show', $video->user->name) }}" class="flex items-center gap-4">
                        @if($video->user->avatar)
                            <img src="{{ asset('storage/' . $video->user->avatar) }}" alt="{{ $video->user->name }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($video->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $video->user->name }}</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $video->user->videos->count() }} videos</p>
                        </div>
                    </a>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ number_format($video->views) }} views • {{ $video->created_at->diffForHumans() }}
                    </div>
                </div>

                @if($video->description)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $video->description }}</p>
                    </div>
                @endif

                <!-- Reactions -->
                @auth
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 flex-wrap">
                        @php
                            $reactionTypes = ['like' => '👍', 'love' => '❤️', 'laugh' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😠'];
                            $reactionCounts = $video->getReactionCounts();
                            $userReaction = $video->reactions()->where('user_id', auth()->id())->first();
                        @endphp

                        @foreach($reactionTypes as $type => $emoji)
                            <button
                                onclick="handleReaction('{{ $type }}', {{ $video->id }})"
                                class="reaction-btn px-3 py-2 rounded-lg border transition {{ $userReaction && $userReaction->type === $type ? 'bg-blue-100 dark:bg-blue-900 border-blue-500' : 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                                id="reaction-{{ $type }}"
                            >
                                <span class="text-xl">{{ $emoji }}</span>
                                <span class="ml-1 text-sm font-semibold reaction-count-{{ $type }}">{{ $reactionCounts[$type] ?? 0 }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @endauth

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    @if($video->duration)
                        <span>Duration: {{ gmdate('i:s', $video->duration) }}</span>
                        <span>•</span>
                    @endif
                    @if($video->file_size)
                        <span>Size: {{ number_format($video->file_size / 1048576, 2) }} MB</span>
                        <span>•</span>
                    @endif
                    <span>Privacy: {{ ucfirst($video->privacy) }}</span>
                </div>
            </div>
        </div>

        <!-- Related Videos -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Up Next</h2>
            <div id="relatedVideosList" class="space-y-4">
                @foreach($relatedVideos as $index => $related)
                    <a href="{{ route('videos.show', $related->id) }}"
                       class="flex gap-2 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded-lg transition {{ $index === 0 ? 'ring-2 ring-blue-500' : '' }}"
                       data-video-id="{{ $related->id }}"
                       data-video-url="{{ route('videos.show', $related->id) }}">
                        @if($related->thumbnail_path)
                            <img src="{{ asset('storage/' . $related->thumbnail_path) }}" alt="{{ $related->title }}" class="w-40 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-40 h-24 bg-gray-700 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🎥</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">{{ $related->title }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format($related->views) }} views
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $related->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($relatedVideos->isEmpty())
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">No more videos</p>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('videoPlayer');
    const autoplayToggle = document.getElementById('autoplayToggle');
    const upNextTimer = document.getElementById('upNextTimer');
    const countdownEl = document.getElementById('countdown');
    const relatedVideos = document.querySelectorAll('#relatedVideosList a');
    const unmuteOverlay = document.getElementById('unmuteOverlay');

    let countdownInterval = null;
    let countdownSeconds = 5;
    let hasUnmuted = localStorage.getItem('videoUnmuted') === 'true';

    // Load autoplay preference from localStorage
    const autoplayEnabled = localStorage.getItem('videoAutoplay') !== 'false';
    autoplayToggle.checked = autoplayEnabled;

    // Try to autoplay
    const playPromise = videoPlayer.play();

    if (playPromise !== undefined) {
        playPromise.then(() => {
            // Autoplay started successfully
            console.log('Video autoplaying');

            // If user has previously unmuted, unmute this video too
            if (hasUnmuted) {
                videoPlayer.muted = false;
            } else {
                // Show unmute overlay for first-time users
                setTimeout(() => {
                    if (videoPlayer.muted && !videoPlayer.paused) {
                        unmuteOverlay.classList.remove('hidden');
                    }
                }, 2000);
            }
        }).catch(error => {
            // Autoplay was prevented
            console.log('Autoplay prevented:', error);
            // Video will show play button, user needs to interact
        });
    }

    // Handle unmute overlay click
    unmuteOverlay.addEventListener('click', function() {
        videoPlayer.muted = false;
        unmuteOverlay.classList.add('hidden');
        localStorage.setItem('videoUnmuted', 'true');
        hasUnmuted = true;

        // Ensure video is playing
        if (videoPlayer.paused) {
            videoPlayer.play();
        }
    });

    // Hide overlay when user manually unmutes
    videoPlayer.addEventListener('volumechange', function() {
        if (!videoPlayer.muted) {
            unmuteOverlay.classList.add('hidden');
            localStorage.setItem('videoUnmuted', 'true');
            hasUnmuted = true;
        }
    });

    // Save autoplay preference
    autoplayToggle.addEventListener('change', function() {
        localStorage.setItem('videoAutoplay', this.checked);
        if (!this.checked && countdownInterval) {
            clearInterval(countdownInterval);
            upNextTimer.classList.add('hidden');
        }
    });

    // Handle video end
    videoPlayer.addEventListener('ended', function() {
        if (autoplayToggle.checked && relatedVideos.length > 0) {
            startCountdown();
        }
    });

    function startCountdown() {
        const nextVideo = relatedVideos[0];
        if (!nextVideo) return;

        upNextTimer.classList.remove('hidden');
        countdownSeconds = 5;
        countdownEl.textContent = countdownSeconds;

        countdownInterval = setInterval(function() {
            countdownSeconds--;
            countdownEl.textContent = countdownSeconds;

            if (countdownSeconds <= 0) {
                clearInterval(countdownInterval);
                // Navigate to next video
                window.location.href = nextVideo.dataset.videoUrl;
            }
        }, 1000);
    }

    // Allow user to cancel countdown
    upNextTimer.addEventListener('click', function() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            upNextTimer.classList.add('hidden');
        }
    });

    // Scroll to top when video starts
    videoPlayer.addEventListener('play', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Try to play video when user interacts with page (for browsers that block autoplay)
    document.body.addEventListener('click', function playOnInteraction() {
        if (videoPlayer.paused) {
            videoPlayer.play().then(() => {
                console.log('Video started after user interaction');
            }).catch(e => {
                console.log('Could not play video:', e);
            });
        }
    }, { once: true });
});
</script>
@endsection
