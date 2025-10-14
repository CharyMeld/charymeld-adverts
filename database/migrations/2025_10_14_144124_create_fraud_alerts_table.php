<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // new_account_high_value, click_fraud, payment_fraud, etc.
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('ip_address')->nullable();
            $table->json('data')->nullable(); // Additional fraud data
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'false_positive'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['severity', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fraud_alerts');
    }
};
