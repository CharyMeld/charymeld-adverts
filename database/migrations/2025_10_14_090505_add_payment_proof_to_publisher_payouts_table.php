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
        Schema::table('publisher_payouts', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('notes');
            $table->text('admin_notes')->nullable()->after('payment_proof');
            $table->timestamp('paid_at')->nullable()->after('processed_at');
            $table->timestamp('cancelled_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publisher_payouts', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'admin_notes', 'paid_at', 'cancelled_at']);
        });
    }
};
