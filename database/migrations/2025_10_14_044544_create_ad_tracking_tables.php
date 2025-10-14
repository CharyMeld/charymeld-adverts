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
        // Track every ad impression (view)
        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->foreignId('publisher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('country_code', 2)->nullable(); // NG, US, etc
            $table->string('device_type', 20)->nullable(); // mobile, desktop, tablet
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('referrer')->nullable();
            $table->string('page_url')->nullable(); // Where ad was shown
            $table->timestamp('created_at');

            // Indexes for performance
            $table->index('advert_id');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index(['advert_id', 'created_at']);
        });

        // Track every ad click
        Schema::create('ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->foreignId('impression_id')->nullable()->constrained('ad_impressions')->onDelete('cascade');
            $table->foreignId('publisher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('referrer')->nullable();
            $table->string('destination_url')->nullable(); // Where click went
            $table->boolean('is_valid')->default(true); // Fraud detection result
            $table->decimal('cost', 10, 4)->default(0); // Cost of this click
            $table->timestamp('created_at');

            // Indexes
            $table->index('advert_id');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index('is_valid');
            $table->index(['advert_id', 'created_at']);
        });

        // Track conversions (optional - for CPA campaigns)
        Schema::create('ad_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->foreignId('click_id')->constrained('ad_clicks')->onDelete('cascade');
            $table->string('conversion_type')->nullable(); // sale, signup, download, etc
            $table->decimal('conversion_value', 12, 2)->nullable(); // Sale amount
            $table->string('ip_address', 45);
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index('advert_id');
            $table->index('created_at');
        });

        // Daily aggregated stats for performance
        Schema::create('ad_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->date('date');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('ctr', 5, 2)->default(0); // Click-through rate
            $table->decimal('cost', 12, 2)->default(0); // Daily spend
            $table->decimal('revenue', 12, 2)->default(0); // For publishers
            $table->timestamps();

            $table->unique(['advert_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_daily_stats');
        Schema::dropIfExists('ad_conversions');
        Schema::dropIfExists('ad_clicks');
        Schema::dropIfExists('ad_impressions');
    }
};
