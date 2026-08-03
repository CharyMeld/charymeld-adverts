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
        Schema::table('chat_conversations', function (Blueprint $table) {
            // Make user_id nullable to support guest users
            $table->foreignId('user_id')->nullable()->change();

            // Add session_id for guest users
            $table->string('session_id')->nullable()->after('user_id')->index();

            // Add guest email for follow-up (optional)
            $table->string('guest_email')->nullable()->after('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->dropColumn(['session_id', 'guest_email']);
        });
    }
};
