# Hostinger Deployment Guide - HTTPS Configuration

This guide covers deploying CharyMeld Adverts to Hostinger with HTTPS enabled.

## Prerequisites

- Hostinger hosting account with SSL certificate
- Domain pointed to Hostinger nameservers
- Database created in Hostinger cPanel
- SSH access enabled (recommended)

## 1. SSL Certificate Setup

### Enable SSL in Hostinger

1. Log in to Hostinger hPanel
2. Navigate to **SSL** section
3. Install free SSL certificate for your domain
4. Wait 10-15 minutes for SSL activation
5. Verify HTTPS works: `https://yourdomain.com`

## 2. Upload Application Files

### Option A: Via Git (Recommended)

```bash
# SSH into your Hostinger account
ssh u123456789@yourdomain.com

# Navigate to public_html
cd public_html

# Clone repository
git clone https://github.com/CharyMeld/charymeld-adverts.git .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### Option B: Via File Manager

1. Upload all files to `public_html` directory
2. Extract if uploaded as ZIP
3. Run composer via SSH or Hostinger terminal

## 3. Environment Configuration

### Create .env File

```bash
# Copy example file
cp .env.example .env

# Edit with your details
nano .env
```

### Required Settings

```env
APP_NAME="CharyMeld Adverts"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Force HTTPS
FORCE_HTTPS=true

# Database (from Hostinger cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_charymeld
DB_USERNAME=u123456789_user
DB_PASSWORD=your_database_password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Mail (Hostinger SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="CharyMeld Adverts"

# Paystack
PAYSTACK_PUBLIC_KEY=pk_live_xxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxxxxxxxxxx
PAYSTACK_WEBHOOK_URL=https://yourdomain.com/webhook/paystack

# Flutterwave
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_HASH=your_webhook_secret_hash
FLUTTERWAVE_WEBHOOK_URL=https://yourdomain.com/webhook/flutterwave

# Security
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### Generate Application Key

```bash
php artisan key:generate
```

## 4. Force HTTPS Configuration

### Update .htaccess

Create/edit `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Remove public from URL
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Create Middleware for HTTPS Enforcement

Already implemented in `app/Http/Middleware/ForceHttps.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() && config('app.force_https')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
```

## 5. Database Setup

### Run Migrations

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if needed)
php artisan db:seed --force
```

### Create Admin User

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@yourdomain.com',
    'password' => bcrypt('your_secure_password'),
    'role' => 'admin',
    'is_active' => true,
]);
```

## 6. File Permissions

```bash
# Set correct permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Or on Hostinger
chmod -R 755 storage bootstrap/cache
```

## 7. Webhook Configuration

### Paystack Webhook Setup

1. Log in to [Paystack Dashboard](https://dashboard.paystack.com/)
2. Navigate to **Settings** > **Webhooks**
3. Add webhook URL: `https://yourdomain.com/webhook/paystack`
4. Copy the webhook secret and add to `.env`:
   ```env
   PAYSTACK_WEBHOOK_SECRET=your_webhook_secret
   ```

### Flutterwave Webhook Setup

1. Log in to [Flutterwave Dashboard](https://dashboard.flutterwave.com/)
2. Navigate to **Settings** > **Webhooks**
3. Add webhook URL: `https://yourdomain.com/webhook/flutterwave`
4. Set webhook secret hash in `.env`
5. Select events to monitor:
   - `charge.completed`
   - `transfer.completed`

### Test Webhooks

```bash
# Test Paystack webhook
curl -X POST https://yourdomain.com/webhook/paystack \
  -H "Content-Type: application/json" \
  -d '{"event":"charge.success"}'

# Test Flutterwave webhook
curl -X POST https://yourdomain.com/webhook/flutterwave \
  -H "Content-Type: application/json" \
  -d '{"event":"charge.completed"}'
```

## 8. Optimization for Production

### Cache Configuration

```bash
# Cache routes and config
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Clear cache if needed
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Enable OPcache

In Hostinger hPanel:
1. Go to **PHP Configuration**
2. Enable **OPcache**
3. Set recommended values:
   - `opcache.enable=1`
   - `opcache.memory_consumption=128`
   - `opcache.max_accelerated_files=10000`

## 9. Security Checklist

- [ ] SSL certificate active and valid
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secure
- [ ] `.env` file not in version control
- [ ] HTTPS forced via middleware and .htaccess
- [ ] Secure cookies enabled (`SESSION_SECURE_COOKIE=true`)
- [ ] CSRF protection enabled (default in Laravel)
- [ ] Webhook secrets configured
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] Error logging configured (check `storage/logs`)

## 10. Testing Checklist

### SSL/HTTPS Tests

- [ ] `https://yourdomain.com` loads correctly
- [ ] `http://yourdomain.com` redirects to HTTPS
- [ ] SSL certificate shows valid (green padlock)
- [ ] No mixed content warnings
- [ ] Test with [SSL Labs](https://www.ssllabs.com/ssltest/)

### Application Tests

- [ ] Homepage loads
- [ ] User registration works
- [ ] User login works
- [ ] Admin dashboard accessible
- [ ] Campaign creation works
- [ ] File uploads work
- [ ] Emails send correctly

### Payment Gateway Tests

- [ ] Paystack test payment succeeds
- [ ] Paystack webhook receives notifications
- [ ] Flutterwave test payment succeeds
- [ ] Flutterwave webhook receives notifications
- [ ] Transaction records created in database

### Webhook Verification

```bash
# Check Laravel logs for webhook hits
tail -f storage/logs/laravel.log

# Or check webhook endpoints directly
curl -I https://yourdomain.com/webhook/paystack
curl -I https://yourdomain.com/webhook/flutterwave
```

## 11. Monitoring and Maintenance

### Log Monitoring

```bash
# View recent logs
tail -100 storage/logs/laravel.log

# Monitor in real-time
tail -f storage/logs/laravel.log
```

### Database Backups

Set up automated backups in Hostinger:
1. Navigate to **Backups** in hPanel
2. Enable automatic weekly backups
3. Download manual backup before major updates

### Updates

```bash
# Pull latest code
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

## 12. Common Issues

### Issue: Mixed Content Warning

**Solution**: Check all asset URLs use HTTPS
```php
// In blade templates
{{ secure_asset('css/app.css') }}
{{ secure_url('path') }}
```

### Issue: Webhook Not Receiving Data

**Solution**:
1. Check webhook URL is publicly accessible
2. Verify SSL certificate is valid
3. Check Laravel logs for errors
4. Ensure webhook secret matches
5. Test with curl command

### Issue: 500 Internal Server Error

**Solution**:
1. Check `storage/logs/laravel.log`
2. Verify file permissions
3. Check `.env` configuration
4. Clear all caches
5. Ensure database connection works

### Issue: Assets Not Loading

**Solution**:
1. Run `npm run build`
2. Check `public/build` directory exists
3. Verify `.htaccess` configuration
4. Clear browser cache

## Support

For issues specific to:
- **Hostinger**: Contact Hostinger support
- **Paystack**: Check [Paystack Documentation](https://paystack.com/docs/)
- **Flutterwave**: Check [Flutterwave Documentation](https://developer.flutterwave.com/docs)
- **Laravel**: Check [Laravel Documentation](https://laravel.com/docs)

## Quick Reference

### Useful Commands

```bash
# Check PHP version
php -v

# Check Laravel version
php artisan --version

# List all routes
php artisan route:list

# Create admin user
php artisan tinker

# Check database connection
php artisan db:show

# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize
```

### Hostinger Paths

- **Application Root**: `/home/u123456789/domains/yourdomain.com/public_html`
- **Public Directory**: `/home/u123456789/domains/yourdomain.com/public_html/public`
- **Storage**: `/home/u123456789/domains/yourdomain.com/public_html/storage`
- **Logs**: `/home/u123456789/domains/yourdomain.com/public_html/storage/logs`

---

**Last Updated**: 2025-10-15
**Version**: 1.0
