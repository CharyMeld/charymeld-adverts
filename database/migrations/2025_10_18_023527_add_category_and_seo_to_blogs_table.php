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
        Schema::table('blogs', function (Blueprint $table) {
            // Add category relationship
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained('blog_categories')->onDelete('set null');

            // Add SEO fields
            $table->string('meta_title')->nullable()->after('title');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');

            // Add excerpt field
            $table->text('excerpt')->nullable()->after('content');

            // Add reading time
            $table->integer('reading_time')->default(0)->after('views'); // in minutes

            // Add tags (JSON field)
            $table->json('tags')->nullable()->after('reading_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'meta_title', 'meta_description', 'meta_keywords', 'excerpt', 'reading_time', 'tags']);
        });
    }
};
