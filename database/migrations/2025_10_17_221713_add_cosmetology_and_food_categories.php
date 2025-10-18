<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add Cosmetology category
        Category::create([
            'name' => 'Cosmetology',
            'slug' => 'cosmetology',
            'description' => 'Beauty, makeup, skincare, hair care, and cosmetic products',
            'icon' => '💄',
            'parent_id' => null,
            'is_active' => true,
        ]);

        // Add Food and Beverages category
        Category::create([
            'name' => 'Food and Beverages',
            'slug' => 'food-and-beverages',
            'description' => 'Food items, beverages, restaurants, and catering services',
            'icon' => '🍔',
            'parent_id' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Category::whereIn('slug', ['cosmetology', 'food-and-beverages'])->delete();
    }
};
