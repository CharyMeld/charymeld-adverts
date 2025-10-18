<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Miss You! - CharyMeld Adverts</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: white;
            font-size: 28px;
            margin: 0;
        }
        .subtitle {
            font-size: 16px;
            color: #e0e7ff;
            margin-top: 10px;
        }
        h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .highlight-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .highlight-box h3 {
            color: #92400e;
            font-size: 18px;
            margin: 0 0 10px 0;
        }
        .highlight-box p {
            color: #78350f;
            font-size: 14px;
            margin: 5px 0;
        }
        .advert-card {
            background-color: #f9fafb;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #8b5cf6;
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
            color: #8b5cf6;
            font-weight: bold;
            margin: 8px 0;
        }
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .feature-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .feature-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .feature-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .feature-text {
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #8b5cf6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #7c3aed;
        }
        .button-secondary {
            background-color: #3B82F6;
        }
        .button-secondary:hover {
            background-color: #2563EB;
        }
        .stats {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .stats h3 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e7ff;
        }
        .stat-item:last-child {
            border-bottom: none;
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
            <h1>We Miss You, {{ $user->name }}! 👋</h1>
            <div class="subtitle">It's been a while since your last visit...</div>
        </div>

        <p>Hello {{ $user->name }},</p>

        <p>We noticed you haven't been active on CharyMeld Adverts recently, and we wanted to reach out! A lot has happened since your last visit, and we'd love to have you back.</p>

        <div class="highlight-box">
            <h3>🎁 Special Welcome Back Offer!</h3>
            <p><strong>Get 20% OFF</strong> your next featured ad listing</p>
            <p>Use code: <strong style="font-size: 18px;">WELCOME20</strong></p>
            <p style="font-size: 12px; margin-top: 10px;">Valid for the next 7 days</p>
        </div>

        <h2>✨ What's New on CharyMeld</h2>

        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">🤖</div>
                <div class="feature-title">AI Assistant</div>
                <div class="feature-text">Get instant help with our smart chatbot</div>
            </div>
            <div class="feature-box">
                <div class="feature-icon">📱</div>
                <div class="feature-title">Mobile App</div>
                <div class="feature-text">Install our PWA for better experience</div>
            </div>
            <div class="feature-box">
                <div class="feature-icon">🔍</div>
                <div class="feature-title">Advanced Search</div>
                <div class="feature-text">Find exactly what you need faster</div>
            </div>
            <div class="feature-box">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Analytics</div>
                <div class="feature-text">Track your ad performance</div>
            </div>
        </div>

        @if($personalizedAdverts && count($personalizedAdverts) > 0)
        <h2>🎯 Recommended Just For You</h2>
        <p>Based on your previous activity, you might be interested in:</p>
        @foreach($personalizedAdverts as $advert)
        <div class="advert-card">
            <div class="advert-title">{{ $advert->title }}</div>
            <div class="advert-meta">
                📍 {{ $advert->location }} | 📁 {{ $advert->category->name ?? 'General' }}
            </div>
            @if($advert->price)
            <div class="advert-price">{{ $advert->currency ?? '₦' }}{{ number_format($advert->price, 2) }}</div>
            @endif
            <a href="{{ route('adverts.show', $advert->slug) }}" class="button" style="margin: 10px 0; font-size: 14px; padding: 8px 20px;">View Details</a>
        </div>
        @endforeach
        @endif

        <div class="stats">
            <h3>📈 Your Account Summary</h3>
            <div class="stat-item">
                <span>Member Since</span>
                <strong>{{ $user->created_at->format('F Y') }}</strong>
            </div>
            <div class="stat-item">
                <span>Total Ads Posted</span>
                <strong>{{ $user->adverts()->count() }}</strong>
            </div>
            <div class="stat-item">
                <span>Total Views</span>
                <strong>{{ $user->adverts()->sum('views') }}</strong>
            </div>
            <div class="stat-item">
                <span>Saved Searches</span>
                <strong>{{ $user->savedSearches()->count() ?? 0 }}</strong>
            </div>
        </div>

        <div style="background-color: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin: 20px 0;">
            <p style="margin: 0; color: #065f46;"><strong>💡 Did you know?</strong> Active users get 3x more engagement on their listings. Come back and boost your ads today!</p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('home') }}" class="button">Browse Latest Ads</a>
            <a href="{{ route('advertiser.adverts.create') }}" class="button button-secondary">Post an Ad</a>
        </div>

        <p>We've made significant improvements to make your experience better than ever. Your feedback has helped shape these updates!</p>

        <p>If there's anything we can do to improve your experience, please don't hesitate to reach out. We're here to help!</p>

        <p style="margin-top: 30px;">Hope to see you soon!</p>
        <p><strong>The CharyMeld Team</strong></p>

        <div class="footer">
            <p>This email was sent to {{ $user->email }}</p>
            <p>&copy; {{ date('Y') }} CharyMeld Adverts. All rights reserved.</p>
            <p>
                <a href="{{ route('home') }}" style="color: #3B82F6; text-decoration: none;">Visit Our Website</a> |
                <a href="{{ route('profile.edit') }}" style="color: #666; text-decoration: none;">Update Preferences</a>
            </p>
        </div>
    </div>
</body>
</html>
