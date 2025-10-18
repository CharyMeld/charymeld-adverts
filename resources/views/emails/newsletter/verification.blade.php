<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Newsletter Subscription</title>
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
        .note {
            background-color: #f0f9ff;
            padding: 15px;
            border-left: 4px solid #3B82F6;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="CharyMeld Adverts">
        </div>

        <h1>Verify Your Email Address</h1>

        <p>Hello {{ $subscriber->name ?? 'there' }},</p>

        <p>Thank you for subscribing to CharyMeld Adverts newsletter! We're excited to have you join our community.</p>

        <p>To complete your subscription and start receiving our weekly digest of top ads, trending categories, and exclusive updates, please verify your email address by clicking the button below:</p>

        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
        </div>

        <div class="note">
            <strong>Note:</strong> If you didn't subscribe to our newsletter, you can safely ignore this email. Your email address will not be added to our mailing list.
        </div>

        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #3B82F6;">{{ $verificationUrl }}</p>

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
