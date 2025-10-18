{{-- SEO Meta Tags Component --}}

{{-- Basic Meta Tags --}}
<title>{{ $seo['title'] ?? config('app.name') }}</title>
<meta name="description" content="{{ $seo['description'] ?? 'CharyMeld Adverts - Your trusted marketplace' }}">
<meta name="keywords" content="{{ $seo['keywords'] ?? 'adverts, marketplace, buy, sell' }}">

{{-- Canonical URL --}}
@if(isset($seo['canonical']))
<link rel="canonical" href="{{ $seo['canonical'] }}">
@endif

{{-- Open Graph Meta Tags --}}
@if(isset($seo['og']))
<meta property="og:title" content="{{ $seo['og']['title'] ?? $seo['title'] }}">
<meta property="og:description" content="{{ $seo['og']['description'] ?? $seo['description'] }}">
<meta property="og:type" content="{{ $seo['og']['type'] ?? 'website' }}">
<meta property="og:url" content="{{ $seo['og']['url'] ?? url()->current() }}">
<meta property="og:site_name" content="{{ $seo['og']['site_name'] ?? config('app.name') }}">

@if(isset($seo['og']['image']))
<meta property="og:image" content="{{ $seo['og']['image'] }}">
<meta property="og:image:alt" content="{{ $seo['og']['title'] ?? $seo['title'] }}">
@endif

@if(isset($seo['og']['published_time']))
<meta property="article:published_time" content="{{ $seo['og']['published_time'] }}">
@endif

@if(isset($seo['og']['author']))
<meta property="article:author" content="{{ $seo['og']['author'] }}">
@endif
@endif

{{-- Twitter Card Meta Tags --}}
@if(isset($seo['twitter']))
<meta name="twitter:card" content="{{ $seo['twitter']['card'] ?? 'summary' }}">
<meta name="twitter:title" content="{{ $seo['twitter']['title'] ?? $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['twitter']['description'] ?? $seo['description'] }}">

@if(isset($seo['twitter']['image']))
<meta name="twitter:image" content="{{ $seo['twitter']['image'] }}">
@endif

@if(isset($seo['twitter']['site']))
<meta name="twitter:site" content="{{ $seo['twitter']['site'] }}">
@endif
@endif

{{-- Schema.org JSON-LD --}}
@if(isset($seo['schema']))
<script type="application/ld+json">
{!! $seo['schema'] !!}
</script>
@endif

{{-- Additional SEO Meta Tags --}}
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">
<meta name="author" content="{{ config('app.name') }}">
