<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('onboarding_completed')->default(false)->after('email_verified_at');
            $table->json('completed_tours')->nullable()->after('onboarding_completed');
            $table->timestamp('last_tour_prompted_at')->nullable()->after('completed_tours');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed', 'completed_tours', 'last_tour_prompted_at']);
        });
    }
};
