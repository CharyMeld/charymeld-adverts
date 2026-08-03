<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add user_type to users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['advertiser', 'publisher', 'both', 'admin'])->default('advertiser')->after('role');
        });

        // Publisher profiles and settings
        Schema::create('publisher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('website_url');
            $table->string('website_name');
            $table->text('website_description')->nullable();
            $table->string('website_category')->nullable();
            $table->integer('monthly_visitors')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->text('rejection_reason')->nullable();

            // Payment info
            $table->decimal('revenue_share', 5, 2)->default(70); // % publisher gets
            $table->string('payment_method')->nullable(); // bank, paypal, etc
            $table->json('payment_details')->nullable(); // Bank account, PayPal email, etc
            $table->decimal('minimum_payout', 10, 2)->default(5000); // Minimum withdrawal

            $table->timestamps();
        });

        // Ad zones on publisher websites
        Schema::create('publisher_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('users')->onDelete('cascade');
            $table->string('zone_name'); // e.g., "Header Banner", "Sidebar"
            $table->string('zone_code')->unique(); // Unique identifier for embed
            $table->enum('ad_type', ['banner', 'text', 'video', 'any'])->default('any');
            $table->string('size')->nullable(); // 728x90, 300x250, etc
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->timestamps();

            $table->index('zone_code');
        });

        // Publisher earnings tracking
        Schema::create('publisher_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('publisher_zones')->onDelete('set null');
            $table->date('date');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('advertiser_spend', 12, 2)->default(0); // What advertiser paid
            $table->decimal('publisher_revenue', 12, 2)->default(0); // What publisher earned
            $table->decimal('platform_commission', 12, 2)->default(0); // Platform's cut
            $table->timestamps();

            $table->index(['publisher_id', 'date']);
            $table->index('date');
        });

        // Publisher payouts/withdrawals
        Schema::create('publisher_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('payment_method');
            $table->json('payment_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('publisher_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_payouts');
        Schema::dropIfExists('publisher_earnings');
        Schema::dropIfExists('publisher_zones');
        Schema::dropIfExists('publisher_profiles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
