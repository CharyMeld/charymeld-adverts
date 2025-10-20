<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'description' => 'Cars, motorcycles, trucks and other vehicles',
                'icon' => 'car',
                'children' => [
                    ['name' => 'Cars', 'slug' => 'cars'],
                    ['name' => 'Motorcycles', 'slug' => 'motorcycles'],
                    ['name' => 'Trucks', 'slug' => 'trucks'],
                    ['name' => 'Auto Parts & Accessories', 'slug' => 'auto-parts-accessories'],
                ]
            ],
            [
                'name' => 'Real Estate',
                'slug' => 'real-estate',
                'description' => 'Houses, apartments, land for sale or rent',
                'icon' => 'home',
                'children' => [
                    ['name' => 'Houses for Sale', 'slug' => 'houses-for-sale'],
                    ['name' => 'Houses for Rent', 'slug' => 'houses-for-rent'],
                    ['name' => 'Land & Plots', 'slug' => 'land-plots'],
                    ['name' => 'Commercial Property', 'slug' => 'commercial-property'],
                ]
            ],
            [
                'name' => 'Jobs',
                'slug' => 'jobs',
                'description' => 'Job listings and career opportunities',
                'icon' => 'briefcase',
                'children' => [
                    ['name' => 'Accounting & Finance', 'slug' => 'accounting-finance'],
                    ['name' => 'Engineering', 'slug' => 'engineering'],
                    ['name' => 'IT & Telecom', 'slug' => 'it-telecom'],
                    ['name' => 'Sales & Marketing', 'slug' => 'sales-marketing'],
                    ['name' => 'Customer Service', 'slug' => 'customer-service'],
                ]
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Phones, computers, TVs and electronics',
                'icon' => 'laptop',
                'children' => [
                    ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
                    ['name' => 'Computers & Laptops', 'slug' => 'computers-laptops'],
                    ['name' => 'TVs & Audio', 'slug' => 'tvs-audio'],
                    ['name' => 'Cameras & Photo', 'slug' => 'cameras-photo'],
                ]
            ],
            [
                'name' => 'Home & Furniture',
                'slug' => 'home-furniture',
                'description' => 'Furniture, home decor and appliances',
                'icon' => 'sofa',
                'children' => [
                    ['name' => 'Furniture', 'slug' => 'furniture'],
                    ['name' => 'Home Appliances', 'slug' => 'home-appliances'],
                    ['name' => 'Garden & Outdoor', 'slug' => 'garden-outdoor'],
                    ['name' => 'Home Decor', 'slug' => 'home-decor'],
                ]
            ],
            [
                'name' => 'Fashion & Beauty',
                'slug' => 'fashion-beauty',
                'description' => 'Clothing, shoes, accessories and beauty products',
                'icon' => 'shirt',
                'children' => [
                    ['name' => 'Clothing', 'slug' => 'clothing'],
                    ['name' => 'Shoes', 'slug' => 'shoes'],
                    ['name' => 'Bags & Accessories', 'slug' => 'bags-accessories'],
                    ['name' => 'Beauty Products', 'slug' => 'beauty-products'],
                ]
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'description' => 'Professional services and business services',
                'icon' => 'wrench',
                'children' => [
                    ['name' => 'Cleaning Services', 'slug' => 'cleaning-services'],
                    ['name' => 'Repair Services', 'slug' => 'repair-services'],
                    ['name' => 'Event Services', 'slug' => 'event-services'],
                    ['name' => 'Professional Services', 'slug' => 'professional-services'],
                ]
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational courses and materials',
                'icon' => 'book',
                'children' => [
                    ['name' => 'Classes & Courses', 'slug' => 'classes-courses'],
                    ['name' => 'Tutors & Lessons', 'slug' => 'tutors-lessons'],
                    ['name' => 'Study Materials', 'slug' => 'study-materials'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $categoryData['slug']], // Find by slug
                $categoryData // Update or create with this data
            );

            foreach ($children as $child) {
                $child['parent_id'] = $parent->id;
                Category::updateOrCreate(
                    ['slug' => $child['slug']], // Find by slug
                    $child // Update or create with this data
                );
            }
        }
    }
}
