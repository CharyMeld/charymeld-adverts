<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Weekly Digest - CharyMeld Adverts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        .header {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }
        .date {
            font-size: 14px;
            color: #e0e7ff;
            margin-top: 5px;
        }
        h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 10px;
        }
        .advert-card {
            background-color: #f9fafb;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #3B82F6;
        }
        .advert-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .advert-meta {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .advert-price {
            font-size: 18px;
            color: #3B82F6;
            font-weight: bold;
            margin: 8px 0;
        }
        .advert-description {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            padding: 8px 20px;
            background-color: #3B82F6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2563EB;
        }
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            background-color: #e0e7ff;
            color: #3730a3;
            border-radius: 20px;
            font-size: 13px;
            margin: 5px 5px 5px 0;
            text-decoration: none;
        }
        .blog-item {
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .blog-item:last-child {
            border-bottom: none;
        }
        .blog-title {
            font-size: 15px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .blog-excerpt {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .stats-box {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .stats-box h3 {
            color: #1f2937;
            font-size: 18px;
            margin: 0 0 10px 0;
        }
        .stats-box p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="CharyMeld Adverts">
        </div>

        <div class="header">
            <h1>Your Weekly Digest</h1>
            <div class="date">{{ now()->format('F d, Y') }}</div>
        </div>

        <p>Hello {{ $subscriber->name ?? 'there' }},</p>

        <p>Here's your weekly roundup of the top ads, trending categories, and latest updates from CharyMeld Adverts!</p>

        @if($topAdverts && count($topAdverts) > 0)
        <h2>🔥 Top Ads This Week</h2>
        @foreach($topAdverts as $advert)
        <div class="advert-card">
            <div class="advert-title">{{ $advert->title }}</div>
            <div class="advert-meta">
                📍 {{ $advert->location }} | 📁 {{ $advert->category->name ?? 'General' }} |
                👁️ {{ $advert->views }} views
            </div>
            @if($advert->price)
            <div class="advert-price">{{ $advert->currency ?? '₦' }}{{ number_format($advert->price, 2) }}</div>
            @endif
            <div class="advert-description">{{ Str::limit($advert->description, 120) }}</div>
            <a href="{{ route('adverts.show', $advert->slug) }}" class="button">View Details</a>
        </div>
        @endforeach
        @endif

        @if($trendingCategories && count($trendingCategories) > 0)
        <h2>📊 Trending Categories</h2>
        <p>Explore the most popular categories this week:</p>
        <div style="margin: 15px 0;">
            @foreach($trendingCategories as $category)
            <a href="{{ route('adverts.category', $category->slug) }}" class="category-badge">
                {{ $category->icon }} {{ $category->name }} ({{ $category->adverts_count ?? 0 }})
            </a>
            @endforeach
        </div>
        @endif

        @if($recentBlogs && count($recentBlogs) > 0)
        <h2>📝 Latest from Our Blog</h2>
        @foreach($recentBlogs as $blog)
        <div class="blog-item">
            <div class="blog-title">{{ $blog->title }}</div>
            <div class="blog-excerpt">{{ Str::limit(strip_tags($blog->content), 100) }}</div>
            <a href="{{ route('blog.show', $blog->slug) }}" style="color: #3B82F6; text-decoration: none; font-size: 13px;">Read more →</a>
        </div>
        @endforeach
        @endif

        <div class="stats-box">
            <h3>Platform Stats</h3>
            <p>🏷️ <strong>{{ \App\Models\Advert::active()->count() }}</strong> active ads</p>
            <p>👥 <strong>{{ \App\Models\User::count() }}</strong> registered users</p>
            <p>📁 <strong>{{ \App\Models\Category::count() }}</strong> categories</p>
        </div>

        <div style="background-color: #fef3c7; padding: 15px; border-radius: 5px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <strong>💡 Pro Tip:</strong> Post your ads during weekdays between 9 AM - 5 PM for maximum visibility!
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('home') }}" class="button">Browse All Ads</a>
        </div>

        <p style="margin-top: 30px;">Thank you for being part of our community!</p>
        <p><strong>The CharyMeld Team</strong></p>

        <div class="footer">
            <p>This email was sent to {{ $subscriber->email }}</p>
            <p>&copy; {{ date('Y') }} CharyMeld Adverts. All rights reserved.</p>
            <p>
                <a href="{{ route('home') }}" style="color: #3B82F6; text-decoration: none;">Visit Our Website</a> |
                <a href="{{ route('newsletter.unsubscribe', $subscriber->email) }}" style="color: #666; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>
