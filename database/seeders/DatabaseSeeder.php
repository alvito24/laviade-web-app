<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Campaign;
use App\Models\CampaignBanner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@laviade.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Test User
        User::create([
            'name' => 'Test User',
            'email' => 'user@laviade.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'T-Shirts', 'slug' => 't-shirts', 'description' => 'Casual & streetwear t-shirts'],
            ['name' => 'Hoodies', 'slug' => 'hoodies', 'description' => 'Comfortable hoodies & sweatshirts'],
            ['name' => 'Jackets', 'slug' => 'jackets', 'description' => 'Stylish jackets & outerwear'],
            ['name' => 'Pants', 'slug' => 'pants', 'description' => 'Joggers, cargo pants & more'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Caps, bags & accessories'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['is_active' => true]));
        }

        // Products seeding removed as per request. Use admin panel to add products.

        // Create Campaign
        $campaign = Campaign::create([
            'name' => 'New Season Collection',
            'slug' => 'new-season-collection',
            'description' => 'Check out our latest streetwear collection',
            'type' => 'hero_slider',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
            'sort_order' => 0,
        ]);

        CampaignBanner::create([
            'campaign_id' => $campaign->id,
            'title' => 'NEW COLLECTION 2026',
            'subtitle' => 'Discover the latest streetwear trends',
            'image' => 'campaigns/hero-banner.jpg',
            'cta_text' => 'Shop Now',
            'cta_link' => '/shop',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Login: admin@laviade.com / password');
        $this->command->info('User Login: user@laviade.com / password');
    }
}
