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
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/advert/{slug}', [HomeController::class, 'show'])->name('advert.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->middleware('bot.detect')->name('contact.submit');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');

// Blog Routes
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Authentication Routes (protected from bots)
Route::middleware(['guest', 'bot.detect'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,10'); // 3 attempts per 10 minutes

    // Password Reset Routes (rate limited)
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// User Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [App\Http\Controllers\VerificationController::class, 'store'])->middleware(['bot.detect', 'throttle:3,10'])->name('verification.store');
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
});

// Chatbot Routes
Route::prefix('assistant')->name('chatbot.')->middleware('auth')->group(function () {
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
