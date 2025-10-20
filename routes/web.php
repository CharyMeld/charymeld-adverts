<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Advertiser\DashboardController as AdvertiserDashboardController;
use App\Http\Controllers\Advertiser\AdvertController as AdvertiserAdvertController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdvertController as AdminAdvertController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.adverts');
Route::get('/csrf-token', [App\Http\Controllers\CsrfController::class, 'token'])->name('csrf.token');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Onboarding Routes
Route::get('/onboarding/tour', [App\Http\Controllers\OnboardingController::class, 'tour'])->name('onboarding.tour');
Route::get('/onboarding/advertiser-tour', [App\Http\Controllers\OnboardingController::class, 'advertiserTour'])->name('onboarding.advertiser');
Route::get('/onboarding/admin-tour', [App\Http\Controllers\OnboardingController::class, 'adminTour'])->name('onboarding.admin');
Route::post('/onboarding/complete', [App\Http\Controllers\OnboardingController::class, 'completeTour'])->name('onboarding.complete');
Route::get('/onboarding/check/{tourType}', [App\Http\Controllers\OnboardingController::class, 'hasTourCompleted'])->name('onboarding.check');
Route::get('/api/search/instant', [App\Http\Controllers\SearchController::class, 'instant'])->name('search.instant');
Route::post('/api/analytics/contact-click', [App\Http\Controllers\Api\AnalyticsController::class, 'trackContactClick'])->name('api.analytics.contact-click');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/advert/{slug}', [HomeController::class, 'show'])->name('advert.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->middleware('bot.detect')->name('contact.submit');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/data-deletion', [HomeController::class, 'dataDeletion'])->name('data-deletion');

// Partner & Press Routes
Route::get('/partners', [App\Http\Controllers\PartnerController::class, 'index'])->name('partners');
Route::post('/partners/submit', [App\Http\Controllers\PartnerController::class, 'submit'])->middleware('bot.detect')->name('partners.submit');
Route::get('/press', [App\Http\Controllers\PartnerController::class, 'press'])->name('press');

// Referral System Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/referrals', [App\Http\Controllers\PartnerController::class, 'referralDashboard'])->name('referrals.dashboard');
    Route::post('/referrals/generate', [App\Http\Controllers\PartnerController::class, 'generateReferralLink'])->name('referrals.generate');
});

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/generate-sitemap', [App\Http\Controllers\SitemapController::class, 'generate'])->name('sitemap.generate');

// Blog Routes
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{blog}/comment', [App\Http\Controllers\BlogCommentController::class, 'store'])->middleware(['bot.detect', 'throttle:5,1'])->name('blog.comment.store');

// RSS Feed Routes
Route::get('/rss/blog', [App\Http\Controllers\RssFeedController::class, 'blog'])->name('rss.blog');
Route::get('/rss/blog/category/{slug}', [App\Http\Controllers\RssFeedController::class, 'category'])->name('rss.blog.category');

// Newsletter Routes
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->middleware(['bot.detect', 'throttle:5,10'])->name('newsletter.subscribe');
Route::get('/newsletter/verify/{token}', [App\Http\Controllers\NewsletterController::class, 'verify'])->name('newsletter.verify');
Route::get('/newsletter/unsubscribe/{email}', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/resubscribe', [App\Http\Controllers\NewsletterController::class, 'resubscribe'])->middleware(['bot.detect', 'throttle:3,10'])->name('newsletter.resubscribe');

// Authentication Routes (protected from bots)
Route::middleware(['guest', 'bot.detect'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,10'); // 3 attempts per 10 minutes

    // Social Login Routes
    Route::get('/auth/{provider}', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'callback'])->name('social.callback');

    // Password Reset Routes (rate limited)
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');

    // Account Recovery Routes (for locked/hacked accounts)
    Route::get('/account-recovery', [App\Http\Controllers\AccountRecoveryController::class, 'create'])->name('account-recovery.create');
    Route::post('/account-recovery', [App\Http\Controllers\AccountRecoveryController::class, 'store'])->middleware('throttle:3,10')->name('account-recovery.store'); // 3 requests per 10 minutes
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Two-Factor Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login/2fa', [App\Http\Controllers\TwoFactorController::class, 'loginVerify'])->name('login.2fa');
    Route::post('/login/2fa', [App\Http\Controllers\TwoFactorController::class, 'loginVerify'])->middleware('throttle:5,1');
});

// User Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [App\Http\Controllers\VerificationController::class, 'store'])->middleware(['bot.detect', 'throttle:3,10'])->name('verification.store');

    // Two-Factor Authentication Settings
    Route::prefix('profile/security')->name('profile.security.')->group(function () {
        Route::get('/2fa', [App\Http\Controllers\TwoFactorController::class, 'index'])->name('2fa');
        Route::get('/2fa/enable', [App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
        Route::post('/2fa/verify', [App\Http\Controllers\TwoFactorController::class, 'verify'])->middleware('throttle:5,1')->name('2fa.verify');
        Route::post('/2fa/disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->middleware('throttle:3,1')->name('2fa.disable');
        Route::get('/2fa/recovery-codes', [App\Http\Controllers\TwoFactorController::class, 'recoveryCodes'])->name('2fa.recovery-codes');
        Route::post('/2fa/recovery-codes/regenerate', [App\Http\Controllers\TwoFactorController::class, 'regenerateRecoveryCodes'])->middleware('throttle:3,10')->name('2fa.recovery-codes.regenerate');
    });

    // Security Reporting Routes
    Route::prefix('security/reports')->name('security.reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\SecurityReportController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SecurityReportController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SecurityReportController::class, 'store'])->middleware(['bot.detect', 'throttle:5,10'])->name('store'); // 5 reports per 10 minutes
        Route::get('/{id}', [App\Http\Controllers\SecurityReportController::class, 'show'])->name('show');
    });

    // Account Recovery Dashboard (for logged-in users to check their recovery requests)
    Route::get('/account-recovery/requests', [App\Http\Controllers\AccountRecoveryController::class, 'index'])->name('account-recovery.index');
});

// Email Verification Routes (optional - can be enabled later)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function () {
    // Email verification logic here
    return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    // Resend verification email logic here
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Advertiser Routes
Route::prefix('advertiser')->name('advertiser.')->middleware(['auth', 'advertiser'])->group(function () {
    Route::get('/dashboard', [AdvertiserDashboardController::class, 'index'])->name('dashboard');

    // Adverts
    Route::get('/adverts', [AdvertiserAdvertController::class, 'index'])->name('adverts.index');
    Route::get('/adverts/create', [AdvertiserAdvertController::class, 'create'])->name('adverts.create');
    Route::post('/adverts', [AdvertiserAdvertController::class, 'store'])->middleware(['bot.detect', 'throttle:10,1'])->name('adverts.store'); // Rate limit: 10 per minute
    Route::get('/adverts/{advert}', [AdvertiserAdvertController::class, 'show'])->name('adverts.show');
    Route::get('/adverts/{advert}/edit', [AdvertiserAdvertController::class, 'edit'])->name('adverts.edit');
    Route::put('/adverts/{advert}', [AdvertiserAdvertController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('adverts.update');
    Route::delete('/adverts/{advert}', [AdvertiserAdvertController::class, 'destroy'])->middleware('throttle:5,1')->name('adverts.destroy');
    Route::get('/adverts/{advert}/payment', [AdvertiserAdvertController::class, 'showPayment'])->name('adverts.payment');
    Route::delete('/advert-images/{image}', [AdvertiserAdvertController::class, 'deleteImage'])->middleware('throttle:10,1')->name('advert-images.delete');

    // Campaigns
    Route::get('/campaigns/create', [AdvertiserAdvertController::class, 'createCampaign'])->name('campaigns.create');
    Route::post('/campaigns', [AdvertiserAdvertController::class, 'storeCampaign'])->middleware(['bot.detect', 'throttle:5,1'])->name('campaigns.store');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\Advertiser\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/campaign/{advert}', [App\Http\Controllers\Advertiser\AnalyticsController::class, 'showCampaign'])->name('analytics.campaign');

    // Reports
    Route::get('/reports/campaign/{advert}/pdf', [App\Http\Controllers\Advertiser\ReportController::class, 'exportCampaignPdf'])->name('reports.campaign.pdf');
    Route::get('/reports/campaign/{advert}/csv', [App\Http\Controllers\Advertiser\ReportController::class, 'exportCampaignCsv'])->name('reports.campaign.csv');
    Route::get('/reports/all-campaigns/pdf', [App\Http\Controllers\Advertiser\ReportController::class, 'exportAllCampaignsPdf'])->name('reports.all-campaigns.pdf');
    Route::get('/reports/all-campaigns/csv', [App\Http\Controllers\Advertiser\ReportController::class, 'exportAllCampaignsCsv'])->name('reports.all-campaigns.csv');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->middleware(['bot.detect', 'throttle:30,1'])->name('messages.store'); // 30 messages per minute
});

// Publisher Routes
Route::prefix('publisher')->name('publisher.')->middleware('auth')->group(function () {
    Route::get('/register', [App\Http\Controllers\Publisher\DashboardController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Publisher\DashboardController::class, 'register'])->middleware(['bot.detect', 'throttle:3,10'])->name('register.submit'); // 3 attempts per 10 minutes
    Route::get('/dashboard', [App\Http\Controllers\Publisher\DashboardController::class, 'index'])->name('dashboard');
    Route::put('/profile', [App\Http\Controllers\Publisher\DashboardController::class, 'updateProfile'])->middleware(['bot.detect', 'throttle:5,1'])->name('profile.update');

    // Zones Management
    Route::get('/zones', [App\Http\Controllers\Publisher\ZoneController::class, 'index'])->name('zones.index');
    Route::get('/zones/create', [App\Http\Controllers\Publisher\ZoneController::class, 'create'])->name('zones.create');
    Route::post('/zones', [App\Http\Controllers\Publisher\ZoneController::class, 'store'])->middleware(['bot.detect', 'throttle:10,1'])->name('zones.store');
    Route::get('/zones/{zone}', [App\Http\Controllers\Publisher\ZoneController::class, 'show'])->name('zones.show');
    Route::get('/zones/{zone}/edit', [App\Http\Controllers\Publisher\ZoneController::class, 'edit'])->name('zones.edit');
    Route::put('/zones/{zone}', [App\Http\Controllers\Publisher\ZoneController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('zones.update');
    Route::delete('/zones/{zone}', [App\Http\Controllers\Publisher\ZoneController::class, 'destroy'])->middleware('throttle:5,1')->name('zones.destroy');
    Route::post('/zones/{zone}/toggle-status', [App\Http\Controllers\Publisher\ZoneController::class, 'toggleStatus'])->middleware('throttle:10,1')->name('zones.toggle-status');

    // Earnings & Payouts
    Route::get('/earnings', [App\Http\Controllers\Publisher\EarningController::class, 'index'])->name('earnings.index');
    Route::get('/earnings/export-csv', [App\Http\Controllers\Publisher\EarningController::class, 'exportCsv'])->name('earnings.export-csv');
    Route::get('/payouts', [App\Http\Controllers\Publisher\EarningController::class, 'payouts'])->name('payouts.index');
    Route::post('/payouts/request', [App\Http\Controllers\Publisher\EarningController::class, 'requestPayout'])->middleware(['fraud.protect', 'throttle:5,60'])->name('payouts.request'); // 5 payout requests per hour max
});

// Payment Routes (protected with fraud detection)
Route::middleware(['auth', 'fraud.protect'])->group(function () {
    Route::post('/payment/paystack/initialize', [PaymentController::class, 'initializePaystack'])->name('payment.paystack.initialize');
    Route::get('/payment/paystack/callback', [PaymentController::class, 'paystackCallback'])->name('payment.paystack.callback');
    Route::post('/payment/flutterwave/initialize', [PaymentController::class, 'initializeFlutterwave'])->name('payment.flutterwave.initialize');
    Route::get('/payment/flutterwave/callback', [PaymentController::class, 'flutterwaveCallback'])->name('payment.flutterwave.callback');
});

// Payment Webhooks (no auth required)
Route::post('/webhook/paystack', [PaymentController::class, 'paystackWebhook'])->name('webhook.paystack');
Route::post('/webhook/flutterwave', [PaymentController::class, 'flutterwaveWebhook'])->name('webhook.flutterwave');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->middleware(['bot.detect', 'throttle:5,1'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->middleware('throttle:10,1')->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->middleware('throttle:5,1')->name('users.destroy');

    // Adverts Management
    Route::get('/adverts', [AdminAdvertController::class, 'index'])->name('adverts.index');
    Route::get('/adverts/{advert}', [AdminAdvertController::class, 'show'])->name('adverts.show');
    Route::post('/adverts/{advert}/approve', [AdminAdvertController::class, 'approve'])->middleware('throttle:30,1')->name('adverts.approve');
    Route::post('/adverts/{advert}/reject', [AdminAdvertController::class, 'reject'])->middleware('throttle:30,1')->name('adverts.reject');
    Route::delete('/adverts/{advert}', [AdminAdvertController::class, 'destroy'])->middleware('throttle:10,1')->name('adverts.destroy');

    // Publishers Management
    Route::get('/publishers', [App\Http\Controllers\Admin\PublisherController::class, 'index'])->name('publishers.index');
    Route::get('/publishers/{publisher}', [App\Http\Controllers\Admin\PublisherController::class, 'show'])->name('publishers.show');
    Route::post('/publishers/{publisher}/approve', [App\Http\Controllers\Admin\PublisherController::class, 'approve'])->middleware('throttle:10,1')->name('publishers.approve');
    Route::post('/publishers/{publisher}/reject', [App\Http\Controllers\Admin\PublisherController::class, 'reject'])->middleware('throttle:10,1')->name('publishers.reject');
    Route::put('/publishers/{publisher}/revenue-share', [App\Http\Controllers\Admin\PublisherController::class, 'updateRevenueShare'])->middleware('throttle:10,1')->name('publishers.update-revenue-share');
    Route::get('/payouts', [App\Http\Controllers\Admin\PublisherController::class, 'payouts'])->name('payouts.index');
    Route::post('/payouts/{payout}/process', [App\Http\Controllers\Admin\PublisherController::class, 'processPayout'])->middleware('throttle:20,1')->name('payouts.process');
    Route::post('/payouts/{payout}/complete', [App\Http\Controllers\Admin\PublisherController::class, 'completePayout'])->middleware('throttle:20,1')->name('payouts.complete');
    Route::post('/payouts/{payout}/cancel', [App\Http\Controllers\Admin\PublisherController::class, 'cancelPayout'])->middleware('throttle:10,1')->name('payouts.cancel');

    // Categories Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->middleware(['bot.detect', 'throttle:10,1'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->middleware('throttle:5,1')->name('categories.destroy');
    Route::post('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->middleware('throttle:10,1')->name('categories.toggle-status');

    // Blogs Management
    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [AdminBlogController::class, 'store'])->middleware(['bot.detect', 'throttle:10,1'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminBlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminBlogController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->middleware('throttle:5,1')->name('blogs.destroy');

    // Blog Categories Management
    Route::get('/blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, 'index'])->name('blog-categories.index');
    Route::get('/blog-categories/create', [App\Http\Controllers\Admin\BlogCategoryController::class, 'create'])->name('blog-categories.create');
    Route::post('/blog-categories', [App\Http\Controllers\Admin\BlogCategoryController::class, 'store'])->middleware(['bot.detect', 'throttle:10,1'])->name('blog-categories.store');
    Route::get('/blog-categories/{blogCategory}/edit', [App\Http\Controllers\Admin\BlogCategoryController::class, 'edit'])->name('blog-categories.edit');
    Route::put('/blog-categories/{blogCategory}', [App\Http\Controllers\Admin\BlogCategoryController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('blog-categories.update');
    Route::delete('/blog-categories/{blogCategory}', [App\Http\Controllers\Admin\BlogCategoryController::class, 'destroy'])->middleware('throttle:5,1')->name('blog-categories.destroy');
    Route::post('/blog-categories/{blogCategory}/toggle-status', [App\Http\Controllers\Admin\BlogCategoryController::class, 'toggleStatus'])->middleware('throttle:10,1')->name('blog-categories.toggle-status');

    // Blog Comments Moderation
    Route::get('/blog-comments', [App\Http\Controllers\BlogCommentController::class, 'index'])->name('blog-comments.index');
    Route::post('/blog-comments/{comment}/approve', [App\Http\Controllers\BlogCommentController::class, 'approve'])->middleware('throttle:20,1')->name('blog-comments.approve');
    Route::post('/blog-comments/{comment}/reject', [App\Http\Controllers\BlogCommentController::class, 'reject'])->middleware('throttle:20,1')->name('blog-comments.reject');
    Route::delete('/blog-comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'destroy'])->middleware('throttle:10,1')->name('blog-comments.destroy');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');

    // Support Chat
    Route::get('/support-chat', [App\Http\Controllers\Admin\SupportChatController::class, 'index'])->name('support-chat.index');
    Route::get('/support-chat/{conversation}', [App\Http\Controllers\Admin\SupportChatController::class, 'show'])->name('support-chat.show');
    Route::post('/support-chat/{conversation}/connect', [App\Http\Controllers\Admin\SupportChatController::class, 'connect'])->middleware('throttle:10,1')->name('support-chat.connect');
    Route::post('/support-chat/{conversation}/messages', [App\Http\Controllers\Admin\SupportChatController::class, 'sendMessage'])->middleware(['bot.detect', 'throttle:60,1'])->name('support-chat.send-message');
    Route::post('/support-chat/{conversation}/resolve', [App\Http\Controllers\Admin\SupportChatController::class, 'resolve'])->middleware('throttle:10,1')->name('support-chat.resolve');
    Route::get('/support-chat/{conversation}/messages', [App\Http\Controllers\Admin\SupportChatController::class, 'getMessages'])->name('support-chat.get-messages');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->middleware('throttle:30,1')->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->middleware('throttle:5,1')->name('notifications.mark-all-read');

    // User Verifications
    Route::get('/verifications', [App\Http\Controllers\VerificationController::class, 'adminIndex'])->name('verifications.index');
    Route::get('/verifications/{verification}', [App\Http\Controllers\VerificationController::class, 'adminShow'])->name('verifications.show');
    Route::post('/verifications/{verification}/approve', [App\Http\Controllers\VerificationController::class, 'approve'])->middleware('throttle:10,1')->name('verifications.approve');
    Route::post('/verifications/{verification}/reject', [App\Http\Controllers\VerificationController::class, 'reject'])->middleware('throttle:10,1')->name('verifications.reject');

    // Security Management
    Route::prefix('security')->name('security.')->group(function () {
        // Security Reports
        Route::get('/reports', [App\Http\Controllers\Admin\SecurityReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{id}', [App\Http\Controllers\Admin\SecurityReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{id}', [App\Http\Controllers\Admin\SecurityReportController::class, 'update'])->middleware('throttle:20,1')->name('reports.update');
        Route::post('/reports/{id}/action', [App\Http\Controllers\Admin\SecurityReportController::class, 'takeAction'])->middleware('throttle:10,1')->name('reports.action');

        // Account Recovery Requests
        Route::get('/recovery-requests', [App\Http\Controllers\Admin\SecurityReportController::class, 'recoveryRequests'])->name('recovery.index');
        Route::get('/recovery-requests/{id}', [App\Http\Controllers\Admin\SecurityReportController::class, 'showRecoveryRequest'])->name('recovery.show');
        Route::put('/recovery-requests/{id}', [App\Http\Controllers\Admin\SecurityReportController::class, 'updateRecoveryRequest'])->middleware('throttle:20,1')->name('recovery.update');
    });
});

// Chatbot Routes - Accessible to all users (guests and authenticated)
Route::prefix('assistant')->name('chatbot.')->group(function () {
    Route::get('/', [App\Http\Controllers\ChatbotController::class, 'index'])->name('index');
    Route::post('/conversations', [App\Http\Controllers\ChatbotController::class, 'store'])->middleware('throttle:10,1')->name('store'); // 10 new conversations per minute
    Route::get('/conversations/{conversation}', [App\Http\Controllers\ChatbotController::class, 'show'])->name('show');
    Route::post('/conversations/{conversation}/messages', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->middleware(['bot.detect', 'throttle:30,1'])->name('send-message'); // 30 messages per minute
    Route::get('/conversations/{conversation}/messages', [App\Http\Controllers\ChatbotController::class, 'getMessages'])->name('get-messages');
    Route::put('/conversations/{conversation}/personality', [App\Http\Controllers\ChatbotController::class, 'updatePersonality'])->middleware('throttle:5,1')->name('update-personality');
    Route::delete('/conversations/{conversation}', [App\Http\Controllers\ChatbotController::class, 'destroy'])->middleware('throttle:5,1')->name('destroy');
});

// API Routes for AJAX
Route::middleware('auth')->group(function () {
    Route::get('/api/messages/unread-count', [MessageController::class, 'unreadCount'])->name('api.messages.unread-count');
});

// Ad Platform API Routes (protected from click fraud & bots)
Route::prefix('api/ad')->name('api.ad.')->middleware(['bot.detect', 'fraud.protect'])->group(function () {
    Route::get('/serve', [App\Http\Controllers\Api\AdController::class, 'serve'])->name('serve');
    Route::get('/click/{id}', [App\Http\Controllers\Api\AdController::class, 'click'])->middleware('throttle:20,1')->name('click'); // 20 clicks per minute max
    Route::get('/impression/{id}', [App\Http\Controllers\Api\AdController::class, 'trackImpression'])->middleware('throttle:60,1')->name('impression'); // 60 impressions per minute
    Route::get('/embed/{zoneCode}', [App\Http\Controllers\Api\AdController::class, 'embed'])->name('embed');

    // Protected route - requires auth
    Route::middleware('auth')->get('/stats/{id}', [App\Http\Controllers\Api\AdController::class, 'stats'])->name('stats');
});

// Feed Routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/feed', [App\Http\Controllers\FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed/react', [App\Http\Controllers\FeedController::class, 'react'])->name('feed.react');
    Route::post('/feed/comment', [App\Http\Controllers\FeedController::class, 'comment'])->name('feed.comment');
    Route::get('/feed/comments/{type}/{id}', [App\Http\Controllers\FeedController::class, 'getComments'])->name('feed.comments');
});

// User Networking Routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile/{username}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'store'])->middleware(['bot.detect', 'throttle:5,1'])->name('profile.store');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('profile.update');

    // Follow routes
    Route::post('/follow/{user}', [App\Http\Controllers\FollowController::class, 'follow'])->middleware('throttle:20,1')->name('follow');
    Route::delete('/unfollow/{user}', [App\Http\Controllers\FollowController::class, 'unfollow'])->middleware('throttle:20,1')->name('unfollow');
    Route::get('/users/{user}/followers', [App\Http\Controllers\FollowController::class, 'followers'])->name('followers');
    Route::get('/users/{user}/following', [App\Http\Controllers\FollowController::class, 'following'])->name('following');

    // Group routes
    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', [App\Http\Controllers\GroupController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\GroupController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GroupController::class, 'store'])->middleware(['bot.detect', 'throttle:5,1'])->name('store');
        Route::get('/{slug}', [App\Http\Controllers\GroupController::class, 'show'])->name('show');
        Route::get('/{slug}/edit', [App\Http\Controllers\GroupController::class, 'edit'])->name('edit');
        Route::put('/{slug}', [App\Http\Controllers\GroupController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('update');
        Route::post('/{slug}/join', [App\Http\Controllers\GroupController::class, 'join'])->middleware('throttle:10,1')->name('join');
        Route::delete('/{slug}/leave', [App\Http\Controllers\GroupController::class, 'leave'])->middleware('throttle:10,1')->name('leave');
        Route::get('/{slug}/members', [App\Http\Controllers\GroupController::class, 'members'])->name('members');
    });

    // Group chat routes
    Route::prefix('groups/{group}/chat')->name('group-chat.')->group(function () {
        Route::get('/', [App\Http\Controllers\GroupChatController::class, 'index'])->name('index');
        Route::post('/messages', [App\Http\Controllers\GroupChatController::class, 'sendMessage'])->middleware(['bot.detect', 'throttle:60,1'])->name('send');
        Route::get('/messages', [App\Http\Controllers\GroupChatController::class, 'getMessages'])->name('messages');
        Route::post('/attachment', [App\Http\Controllers\GroupChatController::class, 'uploadAttachment'])->middleware(['bot.detect', 'throttle:10,1'])->name('attachment');
    });

    // Direct chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [App\Http\Controllers\DirectChatController::class, 'index'])->name('index');
        Route::get('/{user}', [App\Http\Controllers\DirectChatController::class, 'show'])->name('show');
        Route::post('/{user}/messages', [App\Http\Controllers\DirectChatController::class, 'sendMessage'])->middleware(['bot.detect', 'throttle:60,1'])->name('send');
        Route::get('/{user}/messages', [App\Http\Controllers\DirectChatController::class, 'getMessages'])->name('messages');
        Route::post('/{user}/read', [App\Http\Controllers\DirectChatController::class, 'markAsRead'])->middleware('throttle:30,1')->name('read');
        Route::post('/{user}/attachment', [App\Http\Controllers\DirectChatController::class, 'uploadAttachment'])->middleware(['bot.detect', 'throttle:10,1'])->name('attachment');
        Route::get('/unread/count', [App\Http\Controllers\DirectChatController::class, 'unreadCount'])->name('unread-count');
    });

    // Video routes
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [App\Http\Controllers\VideoController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\VideoController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\VideoController::class, 'store'])->middleware(['bot.detect', 'throttle:5,10'])->name('store'); // 5 uploads per 10 minutes
        Route::get('/{video}', [App\Http\Controllers\VideoController::class, 'show'])->name('show');
        Route::get('/{video}/stream', [App\Http\Controllers\VideoController::class, 'stream'])->name('stream');
        Route::get('/{video}/edit', [App\Http\Controllers\VideoController::class, 'edit'])->name('edit');
        Route::put('/{video}', [App\Http\Controllers\VideoController::class, 'update'])->middleware(['bot.detect', 'throttle:10,1'])->name('update');
        Route::delete('/{video}', [App\Http\Controllers\VideoController::class, 'destroy'])->middleware('throttle:5,1')->name('destroy');
    });

    // Reaction routes
    Route::prefix('reactions')->name('reactions.')->group(function () {
        Route::post('/', [App\Http\Controllers\ReactionController::class, 'store'])->middleware(['bot.detect', 'throttle:30,1'])->name('store'); // 30 reactions per minute
        Route::get('/counts', [App\Http\Controllers\ReactionController::class, 'getCounts'])->name('counts');
    });
});
