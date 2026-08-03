# Hostinger Deployment Guide

**Live URL:** https://charymeld-adverts.teamodigitalsolutions.com  
**File Manager path:** `public_html/charymeld-adverts/`  
**Full file manager URL:** https://srv1388-files.hstgr.io/2d9f20806f71f13a/files/public_html/charymeld-adverts/

---

## 1. Subdomain Document Root (Critical)

In Hostinger hPanel → **Subdomains**, set the document root for
`charymeld-adverts.teamodigitalsolutions.com` to:

```
public_html/charymeld-adverts/public
```

> This means Hostinger serves requests from the Laravel `public/` folder directly,
> which is the recommended setup. The root `.htaccess` and `public/.htaccess` also
> handle the routing if the document root cannot be changed.

---

## 2. Upload Files

### Option A — Git (Recommended, requires SSH)

```bash
ssh u[account]@teamodigitalsolutions.com

cd public_html/charymeld-adverts

git clone https://github.com/your-repo/charymeld-adverts.git .

composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### Option B — File Manager / FTP

1. Compress the project (excluding `vendor/`, `node_modules/`, `.env`, `public/build/`).
2. Upload the ZIP to `public_html/charymeld-adverts/` via the Hostinger File Manager.
3. Extract in place.
4. Run `composer install --optimize-autoloader --no-dev` via SSH or Hostinger Terminal.
5. Run `npm install && npm run build` (or upload a pre-built `public/build/` folder).

---

## 3. Environment Configuration

### Copy and edit the .env file

```bash
cp .env.example .env
nano .env   # or use File Manager editor
```

### Minimum required values to fill in

```env
APP_NAME="CharyMeld Adverts"
APP_ENV=production
APP_KEY=                          # filled by: php artisan key:generate
APP_DEBUG=false
APP_URL=https://charymeld-adverts.teamodigitalsolutions.com
APP_DOMAIN=charymeld-adverts.teamodigitalsolutions.com

FORCE_HTTPS=true
APP_FORCE_HTTPS=true

# --- Database (from Hostinger hPanel → MySQL Databases) ---
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# --- Cache/Session (file is safe for shared hosting) ---
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# --- Mail (Hostinger SMTP) ---
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@teamodigitalsolutions.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@teamodigitalsolutions.com"
MAIL_FROM_NAME="CharyMeld Adverts"

# --- Paystack ---
PAYSTACK_PUBLIC_KEY=pk_live_xxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxxxxxxxxxx
PAYSTACK_WEBHOOK_URL=https://charymeld-adverts.teamodigitalsolutions.com/webhook/paystack

# --- Flutterwave ---
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_HASH=your_webhook_secret_hash
FLUTTERWAVE_WEBHOOK_URL=https://charymeld-adverts.teamodigitalsolutions.com/webhook/flutterwave

# --- Security ---
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### Generate application key

```bash
php artisan key:generate
```

---

## 4. Database Setup

```bash
# Run all migrations
php artisan migrate --force

# Seed initial data (optional — only on first deploy)
php artisan db:seed --force
```

---

## 5. Storage & Permissions

```bash
# Create the public storage symlink
php artisan storage:link

# Set permissions
chmod -R 755 storage bootstrap/cache
```

---

## 6. Production Optimisation

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

To clear all caches later:

```bash
php artisan optimize:clear
```

---

## 7. Payment Webhook URLs

Register these exact URLs in your payment dashboards:

| Gateway     | Webhook URL |
|-------------|-------------|
| Paystack    | `https://charymeld-adverts.teamodigitalsolutions.com/webhook/paystack` |
| Flutterwave | `https://charymeld-adverts.teamodigitalsolutions.com/webhook/flutterwave` |

---

## 8. SSL Certificate

1. Hostinger hPanel → **SSL** → install free Let's Encrypt certificate for
   `charymeld-adverts.teamodigitalsolutions.com`.
2. Wait ~10 minutes for activation.
3. HTTPS is enforced by both `public/.htaccess` and the `ForceHttps` middleware.

---

## 9. Post-Deploy Checklist

- [ ] Subdomain document root set to `public_html/charymeld-adverts/public`
- [ ] SSL certificate installed and active
- [ ] `.env` created with production values (never commit this file)
- [ ] `APP_KEY` generated (`php artisan key:generate`)
- [ ] `APP_DEBUG=false`
- [ ] Database credentials correct, migrations run
- [ ] `php artisan storage:link` executed
- [ ] Caches built (`php artisan optimize`)
- [ ] Paystack & Flutterwave webhook URLs updated in dashboards
- [ ] `https://charymeld-adverts.teamodigitalsolutions.com` loads correctly
- [ ] HTTP → HTTPS redirect works
- [ ] Admin login works
- [ ] File uploads work (check `storage/app/public` permissions)

---

## 10. Updating the Application

```bash
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

---

## 11. Useful Commands

```bash
php artisan --version          # Laravel version
php artisan route:list         # List all routes
php artisan db:show            # Check DB connection
tail -100 storage/logs/laravel.log   # View recent errors
```

---

## 12. Common Issues

### 500 Internal Server Error
- Check `storage/logs/laravel.log`
- Verify `.env` exists and `APP_KEY` is set
- Check `storage/` and `bootstrap/cache/` are writable (`chmod -R 755`)

### Assets not loading / 404 on CSS/JS
- Run `npm run build` and ensure `public/build/` is uploaded
- Or run `npm run build` on the server if Node.js is available

### Mixed content warnings
- Ensure `APP_URL` starts with `https://`
- Ensure `FORCE_HTTPS=true` and `APP_FORCE_HTTPS=true` are set

### Session / cookie issues
- Set `SESSION_DOMAIN=charymeld-adverts.teamodigitalsolutions.com` in `.env`
- Ensure `SESSION_SECURE_COOKIE=true`

---

**Last Updated:** 2026-04-12
