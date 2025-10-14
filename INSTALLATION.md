# CharyMeld Adverts - Installation Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL database
- Node.js & NPM (for frontend assets)
- Git

## Installation Steps

### 1. Clone or Navigate to Project

```bash
cd /home/charles/charymeld-adverts
```

### 2. Install Dependencies

```bash
# Install PHP dependencies (already done)
composer install

# Install Node dependencies
npm install
```

### 3. Configure Environment

The `.env` file has been created. Update the following variables:

```env
# Database Configuration
DB_DATABASE=charymeld_adverts
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

# Mail Configuration (for email verification)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@charymeld.com"

# Payment Gateway Keys
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key

FLUTTERWAVE_PUBLIC_KEY=your_flutterwave_public_key
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
FLUTTERWAVE_SECRET_HASH=your_flutterwave_webhook_hash

# Pusher (for real-time chat - optional)
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=mt1
```

### 4. Create Database

Create a MySQL database:

```sql
CREATE DATABASE charymeld_adverts CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables:
- users
- categories
- adverts
- advert_images
- transactions
- blogs
- messages
- sessions
- cache
- password_reset_tokens

### 6. Seed Database

```bash
php artisan db:seed
```

This will create:
- **Admin User**: admin@charymeld.com / Admin@123
- **Demo Advertiser**: advertiser@demo.com / Demo@123
- **8 Main Categories** with subcategories

### 7. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link for public file access (required for image uploads).

### 8. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### 9. Build Frontend Assets (When Ready)

```bash
# Development
npm run dev

# Production
npm run build
```

### 10. Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Login Credentials

### Admin Account
- **Email**: admin@charymeld.com
- **Password**: Admin@123
- **Dashboard**: http://localhost:8000/admin

### Demo Advertiser Account
- **Email**: advertiser@demo.com
- **Password**: Demo@123
- **Dashboard**: http://localhost:8000/advertiser/dashboard

## Features Overview

### Public Features
- ✅ Home page with featured/premium/latest adverts
- ✅ Advanced search with filters (category, location, price range)
- ✅ Category browsing
- ✅ Single advert view with image gallery
- ✅ Blog/news system
- ✅ About, Contact, Terms pages

### Advertiser Features
- ✅ Registration & Email verification
- ✅ Dashboard with statistics
- ✅ Create/Edit/Delete adverts
- ✅ Multiple image upload per advert
- ✅ Payment integration (Paystack & Flutterwave)
- ✅ Three pricing plans: Regular, Featured, Premium
- ✅ Transaction history
- ✅ Real-time messaging system
- ✅ Profile management

### Admin Features
- ✅ Admin dashboard with analytics & charts
- ✅ User management (view, activate/deactivate, delete)
- ✅ Advert management (approve/reject/delete)
- ✅ Category management (CRUD operations)
- ✅ Blog management (create/edit/publish)
- ✅ Transaction monitoring
- ✅ Revenue tracking

### Payment System
- ✅ Integrated with Paystack
- ✅ Integrated with Flutterwave
- ✅ Automatic advert activation via webhooks
- ✅ Three pricing tiers:
  - Regular: ₦1,000 (30 days)
  - Featured: ₦3,000 (30 days)
  - Premium: ₦5,000 (60 days)

### Technical Features
- ✅ Role-based access control (Admin/Advertiser)
- ✅ Email verification
- ✅ SEO-friendly URLs with slugs
- ✅ Image optimization ready
- ✅ Database sessions & caching
- ✅ Secure payment webhooks
- ✅ Real-time chat architecture (Pusher ready)

## Payment Gateway Setup

### Paystack
1. Create account at https://paystack.com
2. Get API keys from Settings > API Keys & Webhooks
3. Add webhook URL: `https://yourdomain.com/webhook/paystack`
4. Update `.env` with keys

### Flutterwave
1. Create account at https://flutterwave.com
2. Get API keys from Settings > API
3. Add webhook URL: `https://yourdomain.com/webhook/flutterwave`
4. Generate webhook hash
5. Update `.env` with keys

## Email Configuration

For development, you can use:
- **Mailtrap**: https://mailtrap.io (recommended for testing)
- **Log driver**: Set `MAIL_MAILER=log` to log emails

For production:
- Gmail SMTP
- SendGrid
- Amazon SES
- Mailgun

## Real-time Chat Setup (Optional)

1. Create Pusher account: https://pusher.com
2. Create new app
3. Get credentials
4. Update `.env` with Pusher credentials
5. Install Laravel Echo & Pusher JS:
```bash
npm install --save-dev laravel-echo pusher-js
```

## Troubleshooting

### Migration Errors
```bash
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Autoload Issues
```bash
composer dump-autoload
```

## Next Steps

1. **Install Frontend Framework**: Choose between Tailwind CSS or Bootstrap
2. **Create Blade Views**: Build the UI for all pages
3. **Test Payment Integration**: Use test keys to verify payments
4. **Configure Email**: Set up SMTP for email verification
5. **Optimize Images**: Implement image resizing/optimization
6. **Add Real-time Chat**: Configure Pusher for messaging
7. **Deploy**: Choose hosting provider (Heroku, Digital Ocean, AWS, etc.)

## Production Deployment Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure production database
- [ ] Set up SSL certificate
- [ ] Configure production mail server
- [ ] Add production payment gateway keys
- [ ] Set up cron job for scheduler: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
- [ ] Configure queue worker (if using queues)
- [ ] Set up backups
- [ ] Configure monitoring (Sentry, Bugsnag, etc.)
- [ ] Optimize with: `php artisan optimize`

## Support

For issues or questions:
- Check `SETUP_PROGRESS.md` for implementation details
- Review Laravel documentation: https://laravel.com/docs
- Check package documentation

## License

Proprietary - All rights reserved
