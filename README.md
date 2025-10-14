# 🛍️ CharyMeld Adverts

A complete enterprise-level classified ads marketplace built with Laravel 11. Features advanced ad management, payment processing, publisher ad network, AI chatbot, fraud protection, and comprehensive KYC verification system.

---

## ✨ Key Features

### 🎯 Core Marketplace
- **Multi-role system**: Admin, Advertiser, Publisher
- **Advanced search & filters**: Category, location, price range, keyword
- **SEO-optimized**: Friendly URLs, meta tags, sitemap ready
- **Advert management**: Create, edit, delete, multi-image upload (up to 5 images)
- **Approval workflow**: Admin moderation system
- **Pricing tiers**: Regular (₦1,000), Featured (₦3,000), Premium (₦5,000)

### 💳 Payment Integration
- **Dual gateway support**: Paystack & Flutterwave
- **Automatic activation**: Webhook-based advert approval
- **Transaction tracking**: Complete payment history
- **Fraud protection**: Multiple failed payment detection, velocity checks

### 📊 Publisher Ad Platform
- **Ad zones management**: Create custom ad spaces
- **Real-time tracking**: Impressions, clicks, conversions
- **Revenue sharing**: Configurable commission rates
- **Analytics dashboard**: Performance metrics and reports
- **Payout system**: Withdrawal requests with admin approval

### 🤖 AI Assistant
- **Context-aware chatbot**: Helps users navigate the platform
- **Step-by-step guidance**: Interactive tutorials
- **Support escalation**: Connect to live admin support
- **Knowledge base**: Pre-configured platform information

### 💬 Live Support System
- **Real-time messaging**: Chat between users and admin
- **Admin notifications**: Instant alerts for support requests
- **Chat history**: Persistent conversation tracking
- **Multiple conversations**: Support multiple users simultaneously

### 🔐 Security & Fraud Protection
- **Bot detection**: Blocks malicious crawlers, allows search engines
- **Rate limiting**: 100 requests/minute per IP
- **Click fraud prevention**: 10 clicks/minute per ad per IP
- **Payment fraud detection**: Velocity checks, geographic anomalies
- **Automated monitoring**: Hourly cron job for suspicious activity
- **XSS/SQL injection protection**: Request pattern validation

### 🆔 KYC Verification System
- **NIN verification**: National Identification Number (11 digits, required)
- **Document uploads**: NIN slip, ID cards, proof of address
- **Bank details**: Secure storage for payouts/refunds
- **Business verification**: CAC registration for corporate accounts
- **Admin approval workflow**: Review and approve/reject submissions
- **Dispute resolution**: Complete user information for issue resolution

### 📈 Analytics & Reporting
- **Dashboard metrics**: Revenue, users, adverts, transactions
- **Campaign analytics**: CTR, impressions, conversions
- **Publisher earnings**: Track revenue per zone
- **Export reports**: PDF and CSV formats
- **Chart visualizations**: Revenue trends, performance graphs

---

## 🚀 Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/CharyMeld/charymeld-adverts.git
cd charymeld-adverts

# 2. Install dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Update .env with your database credentials
# DB_DATABASE=charymeld_adverts
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 5. Create database
mysql -u root -p
CREATE DATABASE charymeld_adverts;
exit;

# 6. Run migrations and seed data
php artisan migrate --seed

# 7. Create storage symlink
php artisan storage:link

# 8. Build frontend assets
npm run build

# 9. Start development server
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔐 Default Login Credentials

### Admin Account
- **URL**: http://localhost:8000/admin
- **Email**: admin@charymeld.com
- **Password**: Admin@123

### Demo Advertiser Account
- **URL**: http://localhost:8000/advertiser/dashboard
- **Email**: advertiser@demo.com
- **Password**: Demo@123

### Demo Publisher Account
- **URL**: http://localhost:8000/publisher/dashboard
- **Email**: publisher@demo.com
- **Password**: Demo@123

---

## 📦 Complete Feature List

### 🔹 User Management
- ✅ Registration with role selection
- ✅ Email verification (ready)
- ✅ Password reset functionality
- ✅ Profile management
- ✅ Role-based access control (Admin/Advertiser/Publisher)
- ✅ User activation/deactivation
- ✅ KYC/NIN verification

### 🔹 Advert System
- ✅ Create/edit/delete adverts
- ✅ Multi-image upload (up to 5 images per advert)
- ✅ Category and subcategory system
- ✅ Pricing tiers (Regular/Featured/Premium)
- ✅ Status tracking (pending/active/expired/rejected)
- ✅ Admin approval workflow
- ✅ Auto-expiration (30 days)
- ✅ View counting
- ✅ Advanced search & filters

### 🔹 Payment Processing
- ✅ Paystack integration (initialize, callback, webhook)
- ✅ Flutterwave integration (initialize, callback, webhook)
- ✅ Transaction logging
- ✅ Automatic advert activation
- ✅ Payment verification
- ✅ Fraud detection (velocity checks, failed payments)

### 🔹 Publisher Platform
- ✅ Zone management (create ad spaces)
- ✅ Ad serving API
- ✅ Click tracking
- ✅ Impression tracking
- ✅ Conversion tracking
- ✅ Earnings calculation
- ✅ Payout requests
- ✅ Admin payout approval
- ✅ Analytics dashboard

### 🔹 Admin Panel
- ✅ Dashboard with analytics
- ✅ User management
- ✅ Advert approval/rejection
- ✅ Category management
- ✅ Blog management
- ✅ Transaction monitoring
- ✅ Publisher management
- ✅ Payout processing
- ✅ Support chat management
- ✅ Verification approval system
- ✅ Fraud alert monitoring
- ✅ Revenue tracking

### 🔹 Communication
- ✅ AI chatbot assistant
- ✅ Live support chat
- ✅ Admin notifications
- ✅ Real-time messaging
- ✅ Chat history
- ✅ Support request system

### 🔹 Security
- ✅ Bot detection middleware
- ✅ Fraud protection middleware
- ✅ Rate limiting (100 req/min)
- ✅ Click fraud prevention
- ✅ Payment fraud detection
- ✅ Automated fraud monitoring (hourly cron)
- ✅ XSS/SQL injection protection
- ✅ Secure document storage

### 🔹 Additional Features
- ✅ Blog system
- ✅ SEO-friendly URLs (slugs)
- ✅ Image optimization
- ✅ Email notifications (ready)
- ✅ Export reports (PDF/CSV)
- ✅ API for ad serving

---

## 🛠️ Technology Stack

- **Framework**: Laravel 11
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates, Tailwind CSS
- **JavaScript**: Vanilla JS, Alpine.js
- **Payment Gateways**: Paystack, Flutterwave
- **Real-time**: Polling-based (upgradeable to Pusher/WebSockets)
- **Cache**: Redis/Memcached recommended
- **Queue**: Database (upgradeable to Redis)

---

## 📁 Project Structure

```
charymeld-adverts/
├── app/
│   ├── Console/Commands/
│   │   ├── CreateUsers.php
│   │   └── MonitorFraudActivity.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   ├── Advertiser/         # Advertiser dashboard
│   │   │   ├── Publisher/          # Publisher platform
│   │   │   ├── Api/                # Ad serving API
│   │   │   ├── Auth/               # Authentication
│   │   │   └── ...
│   │   └── Middleware/
│   │       ├── DetectBots.php      # Bot detection
│   │       ├── FraudProtection.php # Fraud prevention
│   │       ├── IsAdmin.php
│   │       ├── IsAdvertiser.php
│   │       └── IsPublisher.php
│   ├── Models/                      # 20+ Eloquent models
│   └── Services/
│       ├── AIAssistantService.php
│       ├── AdTrackingService.php
│       └── FraudDetectionService.php
├── database/
│   ├── migrations/                  # 15+ migrations
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/                   # Admin panel views
│   │   ├── advertiser/              # Advertiser views
│   │   ├── publisher/               # Publisher views
│   │   ├── verification/            # KYC views
│   │   └── layouts/
│   └── css/
├── routes/
│   ├── web.php                      # All routes with middleware
│   ├── api.php                      # API routes
│   └── console.php                  # Scheduled tasks
└── Documentation/
    ├── INSTALLATION.md              # Setup guide
    ├── FRAUD_PROTECTION_GUIDE.md    # Security documentation
    ├── VERIFICATION_SYSTEM_GUIDE.md # KYC system guide
    ├── PAYMENT_SETUP_GUIDE.md       # Payment integration
    ├── CHATBOT_README.md            # AI assistant docs
    └── LIVE_SUPPORT_FEATURE.md      # Support chat docs
```

---

## ⚙️ Configuration

### Database Configuration

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=charymeld_adverts
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Payment Gateway Configuration

```env
# Paystack
PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxx

# Flutterwave
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-xxxxxxxxxxxxx
FLUTTERWAVE_SECRET_HASH=your_webhook_hash
```

### Cache & Queue (Recommended for Production)

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@charymeld.com
MAIL_FROM_NAME="CharyMeld Adverts"
```

---

## 🔄 Scheduled Tasks

Add to your crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled jobs**:
- Fraud monitoring (hourly)
- Advert expiration check (daily)
- Database cleanup (weekly)

---

## 📚 Documentation

Comprehensive guides are available:

- **[INSTALLATION.md](INSTALLATION.md)** - Complete setup instructions
- **[FRAUD_PROTECTION_GUIDE.md](FRAUD_PROTECTION_GUIDE.md)** - Security features & configuration
- **[VERIFICATION_SYSTEM_GUIDE.md](VERIFICATION_SYSTEM_GUIDE.md)** - KYC/NIN verification guide
- **[PAYMENT_SETUP_GUIDE.md](PAYMENT_SETUP_GUIDE.md)** - Payment gateway integration
- **[CHATBOT_README.md](CHATBOT_README.md)** - AI assistant documentation
- **[LIVE_SUPPORT_FEATURE.md](LIVE_SUPPORT_FEATURE.md)** - Support chat system

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Optimize: `php artisan optimize`
- [ ] Configure Redis for cache & queue
- [ ] Set up cron job for scheduler
- [ ] Configure production payment keys
- [ ] Set up SSL certificate
- [ ] Configure email service (SendGrid/Mailgun)
- [ ] Enable queue workers: `php artisan queue:work`
- [ ] Set up backups (database + uploads)
- [ ] Configure file storage (S3/CloudFlare R2)

### Server Requirements

- PHP >= 8.2
- MySQL >= 8.0 or MariaDB >= 10.3
- Composer
- Node.js & NPM
- Redis (recommended)
- Cron

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=AdvertTest

# Test payment webhooks locally
php artisan serve &
ngrok http 8000
# Update webhook URLs in payment gateway dashboard
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- Laravel Framework
- Paystack & Flutterwave Payment Gateways
- Tailwind CSS
- All open-source contributors

---

## 📞 Support

For issues, questions, or suggestions:

- **Email**: support@charymeld.com
- **GitHub Issues**: [Create an issue](https://github.com/CharyMeld/charymeld-adverts/issues)
- **Documentation**: Check the guides in the project root

---

## 🎯 Roadmap

### Planned Features
- [ ] Mobile app (Flutter)
- [ ] WebSocket support for real-time features
- [ ] Multi-language support
- [ ] Advanced analytics with AI insights
- [ ] Social media integration
- [ ] Auction system for premium ads
- [ ] Subscription plans
- [ ] API for third-party integrations

---

**Version**: 1.0.0
**Laravel**: 11.x
**PHP**: 8.2+
**Last Updated**: October 2025

---

Made with ❤️ by **CharyMeld**
