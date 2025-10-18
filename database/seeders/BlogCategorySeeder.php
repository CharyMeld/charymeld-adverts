<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Advertising Tips',
                'description' => 'Learn effective strategies and tips for creating successful advertisements',
                'icon' => '💡',
                'color' => '#3B82F6', // Blue
                'order' => 1,
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Insights and strategies for digital marketing success',
                'icon' => '📱',
                'color' => '#8B5CF6', // Purple
                'order' => 2,
            ],
            [
                'name' => 'Business & Entrepreneurship',
                'description' => 'Build and grow your business with expert advice',
                'icon' => '💼',
                'color' => '#10B981', // Green
                'order' => 3,
            ],
            [
                'name' => 'Tech & Innovation',
                'description' => 'Stay updated with the latest technology trends and innovations',
                'icon' => '🚀',
                'color' => '#F59E0B', // Amber
                'order' => 4,
            ],
            [
                'name' => 'Success Stories',
                'description' => 'Inspiring stories from successful entrepreneurs and businesses',
                'icon' => '⭐',
                'color' => '#EF4444', // Red
                'order' => 5,
            ],
            [
                'name' => 'News & Updates',
                'description' => 'Latest news and updates from the advertising world',
                'icon' => '📰',
                'color' => '#06B6D4', // Cyan
                'order' => 6,
            ],
            [
                'name' => 'SEO & Content Strategy',
                'description' => 'Master SEO and content marketing strategies',
                'icon' => '📊',
                'color' => '#EC4899', // Pink
                'order' => 7,
            ],
            [
                'name' => 'Freelancing & Remote Work',
                'description' => 'Tips and resources for freelancers and remote workers',
                'icon' => '🏠',
                'color' => '#14B8A6', // Teal
                'order' => 8,
            ],
            [
                'name' => 'Finance & Investment',
                'description' => 'Financial advice and investment strategies',
                'icon' => '💰',
                'color' => '#F97316', // Orange
                'order' => 9,
            ],
            [
                'name' => 'Lifestyle & Inspiration',
                'description' => 'Balance work and life with inspiration and lifestyle tips',
                'icon' => '🌟',
                'color' => '#A855F7', // Purple
                'order' => 10,
            ],
            [
                'name' => 'Tutorials & Guides',
                'description' => 'Step-by-step tutorials and comprehensive guides',
                'icon' => '📚',
                'color' => '#6366F1', // Indigo
                'order' => 11,
            ],
            [
                'name' => 'Events & Promotions',
                'description' => 'Upcoming events, webinars, and special promotions',
                'icon' => '🎉',
                'color' => '#F43F5E', // Rose
                'order' => 12,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'order' => $category['order'],
                'is_active' => true,
            ]);
        }
    }
}
