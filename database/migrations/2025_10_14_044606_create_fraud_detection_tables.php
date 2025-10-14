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
        // IP blacklist for blocking fraudulent IPs
        Schema::create('ip_blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->enum('reason', ['fraud', 'bot', 'abuse', 'spam', 'manual'])->default('fraud');
            $table->text('description')->nullable();
            $table->timestamp('blocked_at');
            $table->timestamp('expires_at')->nullable(); // NULL = permanent
            $table->timestamps();

            $table->index('ip_address');
            $table->index('blocked_at');
        });

        // Fraud detection logs
        Schema::create('fraud_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->nullable()->constrained('adverts')->onDelete('cascade');
            $table->foreignId('click_id')->nullable()->constrained('ad_clicks')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->enum('fraud_type', [
                'rapid_clicks',      // Too many clicks too fast
                'duplicate_ip',      // Same IP clicking repeatedly
                'bot_detected',      // Bot user agent
                'vpn_detected',      // VPN/proxy usage
                'invalid_referrer',  // Suspicious referrer
                'click_farm',        // Click farm pattern
                'other'
            ]);
            $table->integer('severity')->default(1); // 1-10 scale
            $table->text('details')->nullable();
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();

            $table->index('ip_address');
            $table->index('fraud_type');
            $table->index('created_at');
        });

        // Rate limiting per IP
        Schema::create('ip_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->foreignId('advert_id')->nullable()->constrained('adverts')->onDelete('cascade');
            $table->enum('action_type', ['impression', 'click'])->default('click');
            $table->integer('count')->default(1); // Number of actions
            $table->timestamp('window_start')->nullable(); // Start of rate limit window
            $table->timestamp('window_end')->nullable(); // End of rate limit window
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();

            $table->index(['ip_address', 'action_type', 'window_start']);
            $table->index('is_blocked');
        });

        // Known bot user agents
        Schema::create('bot_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('pattern'); // Regex or string pattern
            $table->enum('type', ['bot', 'crawler', 'scraper', 'suspicious'])->default('bot');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed common bot patterns
        DB::table('bot_patterns')->insert([
            ['pattern' => 'bot', 'type' => 'bot', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'crawler', 'type' => 'crawler', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'spider', 'type' => 'bot', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'scraper', 'type' => 'scraper', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'curl', 'type' => 'bot', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'wget', 'type' => 'bot', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'python', 'type' => 'bot', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'Googlebot', 'type' => 'crawler', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['pattern' => 'bingbot', 'type' => 'crawler', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_patterns');
        Schema::dropIfExists('ip_rate_limits');
        Schema::dropIfExists('fraud_logs');
        Schema::dropIfExists('ip_blacklist');
    }
};
