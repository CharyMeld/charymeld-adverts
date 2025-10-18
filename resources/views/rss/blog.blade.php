<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} - @if(isset($category)){{ $category->name }}@else Blog @endif</title>
        <link>{{ url('/') }}</link>
        <description>@if(isset($category)){{ $category->description ?? 'Latest blog posts in ' . $category->name }}@else Latest blog posts from {{ config('app.name') }}@endif</description>
        <language>en-us</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url()->current() }}" rel="self" type="application/rss+xml" />

        @foreach($blogs as $blog)
        <item>
            <title><![CDATA[{{ $blog->title }}]]></title>
            <link>{{ route('blog.show', $blog->slug) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $blog->slug) }}</guid>
            <description><![CDATA[{{ $blog->excerpt ?? strip_tags(Str::limit($blog->content, 200)) }}]]></description>
            <pubDate>{{ $blog->published_at->toRssString() }}</pubDate>
            <author>{{ $blog->user->email }} ({{ $blog->user->name }})</author>
            @if($blog->category)
            <category><![CDATA[{{ $blog->category->name }}]]></category>
            @endif
            @if($blog->featured_image)
            <enclosure url="{{ asset('storage/' . $blog->featured_image) }}" type="image/jpeg" />
            @endif
        </item>
        @endforeach
    </channel>
</rss>
