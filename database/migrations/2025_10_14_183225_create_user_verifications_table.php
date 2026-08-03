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
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // National Identification
            $table->string('nin')->unique()->nullable(); // National Identification Number
            $table->string('nin_document')->nullable(); // Path to NIN document

            // Contact Information
            $table->string('phone_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Nigeria');

            // Additional ID (Optional)
            $table->enum('id_type', ['drivers_license', 'voters_card', 'international_passport', 'other'])->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_document')->nullable(); // Path to ID document

            // Proof of Address (Optional)
            $table->string('proof_of_address_document')->nullable(); // Utility bill, bank statement, etc.

            // Bank Details (for payouts/refunds)
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();

            // Business Information (for sellers/advertisers)
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('business_document')->nullable(); // CAC document, etc.

            // Verification Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'incomplete'])->default('incomplete');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who verified

            // Metadata
            $table->boolean('is_nin_verified')->default(false);
            $table->boolean('is_id_verified')->default(false);
            $table->boolean('is_address_verified')->default(false);
            $table->boolean('is_bank_verified')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('verification_status');
            $table->index('nin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
