<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        Category::query()->delete();

        $categories = [
            [
                'name' => 'Real Estate',
                'description' => 'Properties for sale, rent, or lease',
                'icon' => '🏠',
                'order' => 1,
                'subcategories' => ['Houses', 'Apartments', 'Lands', 'Commercial', 'Short-let']
            ],
            [
                'name' => 'Vehicles',
                'description' => 'Cars, bikes, and other vehicles',
                'icon' => '🚗',
                'order' => 2,
                'subcategories' => ['Cars for Sale', 'Motorcycles', 'Trucks', 'Vehicle Parts']
            ],
            [
                'name' => 'Jobs & Services',
                'description' => 'Employment ads or service providers',
                'icon' => '💼',
                'order' => 3,
                'subcategories' => ['Job Offers', 'Job Seekers', 'Freelancers', 'Repair Services']
            ],
            [
                'name' => 'Electronics & Gadgets',
                'description' => 'All electronic products',
                'icon' => '📱',
                'order' => 4,
                'subcategories' => ['Phones', 'Laptops', 'TVs', 'Accessories']
            ],
            [
                'name' => 'Fashion & Beauty',
                'description' => 'Personal items and beauty services',
                'icon' => '👗',
                'order' => 5,
                'subcategories' => ['Clothes', 'Shoes', 'Jewelry', 'Makeup', 'Barbers', 'Salons']
            ],
            [
                'name' => 'Home & Furniture',
                'description' => 'Household goods, décor, and appliances',
                'icon' => '🛋️',
                'order' => 6,
                'subcategories' => ['Furniture', 'Kitchenware', 'Home Appliances']
            ],
            [
                'name' => 'Education & Training',
                'description' => 'Courses, schools, tutors, certifications',
                'icon' => '📚',
                'order' => 7,
                'subcategories' => ['Online Courses', 'Private Tutors', 'Seminars']
            ],
            [
                'name' => 'Events & Entertainment',
                'description' => 'Event planning, tickets, and media',
                'icon' => '🎉',
                'order' => 8,
                'subcategories' => ['DJ Services', 'Venues', 'Event Planners', 'Photography']
            ],
            [
                'name' => 'Pets & Animals',
                'description' => 'Sale, adoption, or animal care',
                'icon' => '🐾',
                'order' => 9,
                'subcategories' => ['Dogs', 'Cats', 'Birds', 'Pet Services']
            ],
            [
                'name' => 'Agriculture',
                'description' => 'Farm products, tools, and services',
                'icon' => '🌾',
                'order' => 10,
                'subcategories' => ['Crops', 'Livestock', 'Equipment']
            ],
            [
                'name' => 'Health & Fitness',
                'description' => 'Medical, fitness, and wellness adverts',
                'icon' => '💪',
                'order' => 11,
                'subcategories' => ['Gyms', 'Supplements', 'Hospitals', 'Trainers']
            ],
            [
                'name' => 'Business & Investments',
                'description' => 'B2B ads, franchises, and business sales',
                'icon' => '💰',
                'order' => 12,
                'subcategories' => ['Startups', 'Investors', 'Office Supplies']
            ],
            [
                'name' => 'Travel & Tourism',
                'description' => 'Tours, transport, and travel agents',
                'icon' => '✈️',
                'order' => 13,
                'subcategories' => ['Flights', 'Car Hire', 'Hotels', 'Vacation Packages']
            ],
            [
                'name' => 'Technology & Software',
                'description' => 'Apps, websites, digital products',
                'icon' => '💻',
                'order' => 14,
                'subcategories' => ['Web Development', 'Software Tools', 'Hosting']
            ],
            [
                'name' => 'Community & Charity',
                'description' => 'Donations, NGOs, social initiatives',
                'icon' => '🤝',
                'order' => 15,
                'subcategories' => ['Fundraisers', 'Volunteering', 'Religious Ads']
            ],
            [
                'name' => 'Matrimonial & Dating',
                'description' => 'Personal ads, matchmaking',
                'icon' => '💑',
                'order' => 16,
                'subcategories' => ['Singles', 'Marriage Proposals']
            ],
            [
                'name' => 'Art, Design & Media',
                'description' => 'Creative work and design services',
                'icon' => '🎨',
                'order' => 17,
                'subcategories' => ['Graphic Design', 'Printing', 'Branding']
            ],
            [
                'name' => 'Construction & Repairs',
                'description' => 'Building, carpentry, and plumbing services',
                'icon' => '🔨',
                'order' => 18,
                'subcategories' => ['Builders', 'Painters', 'Electricians']
            ],
            [
                'name' => 'Food & Restaurant',
                'description' => 'Food products, delivery, and catering',
                'icon' => '🍔',
                'order' => 19,
                'subcategories' => ['Catering Services', 'Restaurants', 'Food Items']
            ],
            [
                'name' => 'Others / Miscellaneous',
                'description' => 'Everything that doesn\'t fit elsewhere',
                'icon' => '📦',
                'order' => 20,
                'subcategories' => ['Miscellaneous Items', 'General Ads']
            ],
        ];

        foreach ($categories as $categoryData) {
            // Create parent category
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'order' => $categoryData['order'],
                'is_active' => true,
            ]);

            $this->command->info("Created parent category: {$parent->name}");

            // Create subcategories
            $subOrder = 1;
            foreach ($categoryData['subcategories'] as $subcategoryName) {
                $subcategory = Category::create([
                    'name' => $subcategoryName,
                    'slug' => Str::slug($parent->name . '-' . $subcategoryName),
                    'description' => $subcategoryName . ' in ' . $parent->name,
                    'parent_id' => $parent->id,
                    'order' => $subOrder++,
                    'is_active' => true,
                ]);

                $this->command->info("  └─ Created subcategory: {$subcategory->name}");
            }
        }

        $this->command->info('✅ Categories seeded successfully!');
        $this->command->info('Total parent categories: ' . Category::whereNull('parent_id')->count());
        $this->command->info('Total subcategories: ' . Category::whereNotNull('parent_id')->count());
    }
}
