{{-- Social Share Buttons Component --}}
@props(['url', 'title' => '', 'description' => ''])

@php
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
@endphp

<div class="social-share-buttons flex gap-2" {{ $attributes }}>
    {{-- Facebook --}}
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       class="social-share-btn facebook bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2 transition"
       title="Share on Facebook"
       onclick="window.open(this.href, 'facebook-share', 'width=580,height=296'); return false;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
        <span class="hidden sm:inline">Share</span>
    </a>

    {{-- Twitter/X --}}
    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
       target="_blank"
       rel="noopener noreferrer"
       class="social-share-btn twitter bg-black hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center gap-2 transition"
       title="Share on X (Twitter)"
       onclick="window.open(this.href, 'twitter-share', 'width=550,height=420'); return false;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
        <span class="hidden sm:inline">Tweet</span>
    </a>

    {{-- LinkedIn --}}
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       class="social-share-btn linkedin bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center gap-2 transition"
       title="Share on LinkedIn"
       onclick="window.open(this.href, 'linkedin-share', 'width=580,height=496'); return false;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
        </svg>
        <span class="hidden sm:inline">Share</span>
    </a>

    {{-- WhatsApp --}}
    <a href="https://wa.me/?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       class="social-share-btn whatsapp bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 transition"
       title="Share on WhatsApp"
       onclick="window.open(this.href, 'whatsapp-share', 'width=580,height=496'); return false;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
        <span class="hidden sm:inline">Share</span>
    </a>

    {{-- Copy Link --}}
    <button type="button"
            onclick="navigator.clipboard.writeText('{{ $url }}').then(() => { this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M9 2a1 1 0 000 2h2a1 1 0 100-2H9z\'/><path fill-rule=\'evenodd\' d=\'M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\' clip-rule=\'evenodd\'/></svg><span class=\'hidden sm:inline\'>Copied!</span>'; setTimeout(() => { this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'/></svg><span class=\'hidden sm:inline\'>Copy Link</span>'; }, 2000); })"
            class="social-share-btn copy bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 transition cursor-pointer"
            title="Copy link to clipboard">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <span class="hidden sm:inline">Copy Link</span>
    </button>
</div>

<style>
    .social-share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
