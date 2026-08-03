<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the sender_type enum to include 'support'
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('user', 'ai', 'support') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('user', 'ai') NOT NULL DEFAULT 'user'");
    }
};
