# CharyMeld Adverts

A complete Laravel 11 classified ads platform similar to ngaadaverts.com with advanced features including payment integration, admin panel, and real-time messaging.

## 🚀 Quick Start

```bash
# 1. Navigate to project
cd /home/charles/charymeld-adverts

# 2. Dependencies already installed, just configure .env
# Update DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Create database
mysql -u root -p
CREATE DATABASE charymeld_adverts;
exit;

# 4. Run migrations and seeders
php artisan migrate --seed

# 5. Create storage link
php artisan storage:link

# 6. Start development server
php artisan serve
```

Visit: http://localhost:8000

## 🔐 Default Login Credentials

### Admin Account
- **URL**: http://localhost:8000/admin
- **Email**: admin@charymeld.com
- **Password**: Admin@123

### Demo Advertiser Account
- **URL**: http://localhost:8000/advertiser/dashboard
- **Email**: advertiser@demo.com
- **Password**: Demo@123

## ✅ What's Implemented (Backend - 100% Complete)

### ✅ Database & Models
- 10 database tables with complete migrations
- 7 Eloquent models with relationships
- Seeders for categories and users

### ✅ Authentication & Authorization
- Login/Register/Logout
- Email verification ready
- Password reset ready
- Role-based access control (Admin/Advertiser)
- Custom middleware for roles

### ✅ Controllers (All Implemented)
- **HomeController**: Public pages, search, categories
- **Auth Controllers**: Login, Register
- **Advertiser Controllers**: Dashboard, Adverts CRUD, Payment
- **Admin Controllers**: Dashboard, Users, Adverts, Categories, Blogs, Transactions
- **BlogController**: Public blog viewing
- **MessageController**: Chat system
- **PaymentController**: Paystack & Flutterwave integration

### ✅ Payment Integration
- Full Paystack integration (initialize, callback, webhook)
- Full Flutterwave integration (initialize, callback, webhook)
- Automatic advert activation via webhooks
- Three pricing plans (Regular ₦1K, Featured ₦3K, Premium ₦5K)
- Transaction logging and tracking

### ✅ Features Implemented
- Advanced search with filters
- Category/subcategory system
- Multi-image upload support
- SEO-friendly URLs (slugs)
- View counting
- Advert approval workflow
- Transaction monitoring
- Blog system
- Messaging system architecture

### ✅ Routes
- Complete route configuration
- Public routes
- Authenticated routes
- Admin routes with middleware
- Payment routes & webhooks
- API routes for AJAX

## 📋 What's Next (Frontend - To Be Done)

### ⏳ Views to Create
1. **Layouts**: app.blade.php, admin.blade.php, guest.blade.php
2. **Auth Pages**: login, register, verify-email, forgot-password
3. **Public Pages**: home, search, category, advert-single
4. **Advertiser Dashboard**: dashboard, adverts list/create/edit, payment, messages
5. **Admin Dashboard**: dashboard, users, adverts, categories, blogs, transactions
6. **Blog Pages**: index, single post
7. **Static Pages**: about, contact, terms

### ⏳ Frontend Assets
1. Install Tailwind CSS or Bootstrap
2. Configure Vite
3. Add JavaScript for:
   - Image upload preview
   - AJAX forms
   - Real-time chat (Pusher)
   - Charts for analytics
   - Search filters

## 📁 Project Structure

```
charymeld-adverts/
├── app/
│   ├── Http/Controllers/ (✅ All implemented)
│   ├── Models/ (✅ All implemented)
│   └── Middleware/ (✅ All implemented)
├── database/
│   ├── migrations/ (✅ All created)
│   └── seeders/ (✅ All created)
├── routes/
│   └── web.php (✅ Complete)
├── .env (✅ Configured)
├── INSTALLATION.md (✅ Complete guide)
├── SETUP_PROGRESS.md (✅ Progress tracker)
└── README.md (this file)
```

## 🎯 Features Overview

### Public Features
- Home page with featured/premium/latest adverts
- Advanced search (keyword, category, location, price)
- Category browsing with subcategories
- Single advert view with image gallery
- Blog/news section
- About, Contact, Terms pages

### Advertiser Features
- Dashboard with statistics
- Create/edit/delete adverts
- Upload up to 5 images per advert
- Choose pricing plan (Regular/Featured/Premium)
- Pay via Paystack or Flutterwave
- View transaction history
- Chat with admin
- Track advert status

### Admin Features
- Dashboard with analytics & charts
- Manage users (activate/deactivate/delete)
- Approve/reject adverts
- Manage categories (CRUD)
- Manage blogs (create/edit/publish)
- View all transactions
- Revenue tracking

## 💳 Payment Gateways

Both payment gateways are fully integrated:

### Paystack
- Initialize payment: `POST /payment/paystack/initialize`
- Callback: `GET /payment/paystack/callback`
- Webhook: `POST /webhook/paystack`

### Flutterwave
- Initialize payment: `POST /payment/flutterwave/initialize`
- Callback: `GET /payment/flutterwave/callback`
- Webhook: `POST /webhook/flutterwave`

## 📖 Documentation

- **INSTALLATION.md** - Detailed setup instructions
- **SETUP_PROGRESS.md** - Development progress tracker
- **README.md** - This file (overview)

## 🔧 Environment Configuration

Update `.env` with your settings:

```env
# Database
DB_DATABASE=charymeld_adverts
DB_USERNAME=root
DB_PASSWORD=your_password

# Payment Gateways
PAYSTACK_PUBLIC_KEY=pk_test_xxx
PAYSTACK_SECRET_KEY=sk_test_xxx

FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-xxx
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-xxx
FLUTTERWAVE_SECRET_HASH=your_webhook_hash

# Mail (for email verification)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

## 🚀 Current Status

**Backend**: ✅ 100% Complete
- All database tables created
- All models with relationships
- All controllers implemented
- All routes configured
- Payment integration complete
- Authentication system ready

**Frontend**: ⏳ Pending
- Views need to be created
- CSS framework to be added
- JavaScript functionality needed

## 🎨 Next Steps

1. Choose CSS framework (Tailwind CSS recommended)
2. Create Blade layouts and components
3. Build all views (auth, public, advertiser, admin)
4. Add JavaScript for interactivity
5. Test payment integration
6. Configure email for verification
7. Deploy to production

## 📞 Support

See `INSTALLATION.md` for detailed setup and troubleshooting.

---

**Version**: 1.0.0
**Laravel**: 11.x
**PHP**: 8.2+
