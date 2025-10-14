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
        Schema::table('adverts', function (Blueprint $table) {
            // Campaign & Budget
            $table->enum('ad_type', ['classified', 'banner', 'text', 'video'])->default('classified')->after('plan');
            $table->decimal('budget', 12, 2)->nullable()->after('price'); // Total budget
            $table->decimal('daily_budget', 10, 2)->nullable()->after('budget');
            $table->decimal('spent', 12, 2)->default(0)->after('daily_budget'); // Amount spent
            $table->enum('pricing_model', ['cpc', 'cpm', 'cpa', 'flat'])->default('flat')->after('spent');
            $table->decimal('cpc_rate', 8, 2)->nullable()->after('pricing_model'); // Cost per click
            $table->decimal('cpm_rate', 8, 2)->nullable()->after('cpc_rate'); // Cost per 1000 impressions

            // Tracking
            $table->integer('impressions')->default(0)->after('views'); // How many times shown
            $table->integer('clicks')->default(0)->after('impressions'); // How many clicks
            $table->decimal('ctr', 5, 2)->default(0)->after('clicks'); // Click-through rate %

            // Targeting (stored as JSON)
            $table->json('target_countries')->nullable()->after('location'); // ['NG', 'GH', 'KE']
            $table->json('target_devices')->nullable()->after('target_countries'); // ['mobile', 'desktop', 'tablet']
            $table->json('target_keywords')->nullable()->after('target_devices'); // ['cars', 'electronics']
            $table->integer('target_age_min')->nullable()->after('target_keywords');
            $table->integer('target_age_max')->nullable()->after('target_age_min');
            $table->json('target_gender')->nullable()->after('target_age_max'); // ['male', 'female', 'all']

            // Banner specific
            $table->string('banner_image')->nullable()->after('email');
            $table->string('banner_size')->nullable()->after('banner_image'); // 728x90, 300x250, etc
            $table->text('banner_url')->nullable()->after('banner_size'); // Click destination

            // Campaign dates
            $table->timestamp('campaign_start')->nullable()->after('published_at');
            $table->timestamp('campaign_end')->nullable()->after('campaign_start');

            // Status
            $table->boolean('is_paused')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn([
                'ad_type', 'budget', 'daily_budget', 'spent', 'pricing_model',
                'cpc_rate', 'cpm_rate', 'impressions', 'clicks', 'ctr',
                'target_countries', 'target_devices', 'target_keywords',
                'target_age_min', 'target_age_max', 'target_gender',
                'banner_image', 'banner_size', 'banner_url',
                'campaign_start', 'campaign_end', 'is_paused'
            ]);
        });
    }
};
