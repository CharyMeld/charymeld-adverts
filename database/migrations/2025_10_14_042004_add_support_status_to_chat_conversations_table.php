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
            $table->enum('support_status', ['ai_only', 'requested', 'connected', 'resolved'])->default('ai_only')->after('ai_personality');
            $table->foreignId('support_user_id')->nullable()->after('support_status')->constrained('users')->onDelete('set null');
            $table->timestamp('support_requested_at')->nullable()->after('support_user_id');
            $table->timestamp('support_connected_at')->nullable()->after('support_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn(['support_status', 'support_user_id', 'support_requested_at', 'support_connected_at']);
        });
    }
};
