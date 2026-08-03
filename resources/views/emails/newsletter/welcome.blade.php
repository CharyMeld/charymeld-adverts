<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CharyMeld Adverts Newsletter</title>
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
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        h1 {
            color: #3B82F6;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .features {
            margin: 30px 0;
        }
        .feature {
            padding: 15px;
            margin: 10px 0;
            background-color: #f9fafb;
            border-radius: 5px;
            display: flex;
            align-items: flex-start;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3B82F6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2563EB;
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

        <div class="welcome-banner">
            <h1 style="color: white; margin: 0;">Welcome to CharyMeld Adverts!</h1>
            <p style="font-size: 18px; margin: 10px 0 0 0;">Your email has been verified successfully</p>
        </div>

        <p>Hello {{ $subscriber->name ?? 'there' }},</p>

        <p>Thank you for verifying your email! You're now officially part of the CharyMeld Adverts community.</p>

        <h2 style="color: #1f2937; font-size: 18px;">What to Expect:</h2>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">📧</div>
                <div>
                    <strong>Weekly Digest</strong><br>
                    Every Monday, receive curated top ads, trending categories, and new listings in your inbox.
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">🔔</div>
                <div>
                    <strong>Exclusive Updates</strong><br>
                    Be the first to know about new features, special promotions, and platform updates.
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">💡</div>
                <div>
                    <strong>Expert Tips</strong><br>
                    Get advertising tips, digital marketing insights, and success stories from our blog.
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon">🎯</div>
                <div>
                    <strong>Personalized Content</strong><br>
                    Receive recommendations based on your interests and browsing behavior.
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('home') }}" class="button">Explore CharyMeld Now</a>
        </div>

        <p style="margin-top: 30px;">If you have any questions or feedback, feel free to reach out to our support team. We're here to help!</p>

        <p>Happy browsing!</p>
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
