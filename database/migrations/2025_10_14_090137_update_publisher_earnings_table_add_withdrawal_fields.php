<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('publisher_earnings', function (Blueprint $table) {
            // Add amount column (convenience field = publisher_revenue)
            $table->decimal('amount', 12, 2)->default(0)->after('publisher_revenue');

            // Add status for withdrawal tracking
            $table->enum('status', ['pending', 'processing', 'paid'])->default('pending')->after('platform_commission');

            // Add payout reference
            $table->foreignId('payout_id')->nullable()->after('status')->constrained('publisher_payouts')->onDelete('set null');

            $table->index('status');
        });

        // Sync amount with publisher_revenue for existing records
        DB::statement('UPDATE publisher_earnings SET amount = publisher_revenue WHERE amount = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publisher_earnings', function (Blueprint $table) {
            $table->dropForeign(['payout_id']);
            $table->dropColumn(['amount', 'status', 'payout_id']);
        });
    }
};
