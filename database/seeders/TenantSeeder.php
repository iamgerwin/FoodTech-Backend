<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\FoodChain;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\User; // Added User model
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    public function run()
    {
        // Create a tenant (FoodChain)
        $tenant = Tenant::create([
            'id' => Str::uuid(),
            'name' => 'Demo Food Chain',
            'email' => 'demo@foodchain.com',
        ]);

        // Create a restaurant under this tenant
        $restaurant = $tenant->restaurants()->create([
            'id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'name' => 'Demo Restaurant',
            'slug' => Str::slug('Demo Restaurant'),
        ]);

        // Create a dummy manager user (or fetch an existing one)
        $managerUser = User::factory()->create([
            'name' => 'Branch Manager',
            'email' => Str::random(10).'@example.com', // Ensure unique email
            'password' => bcrypt('password'), // Set a default password
        ]);

        // Create a branch for the restaurant
        $branch = $restaurant->branches()->create([
            'id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'manager_id' => $managerUser->id, // Assign manager_id
            'name' => 'Main Branch',
            'address' => '123 Main St, Anytown, USA', // Added dummy address
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'is_active' => true,
            'accepts_orders' => true,
        ]);

        // Create a category for the menu items
        $category = Category::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Courses',
        ]);

        // Create a menu item for the restaurant
        MenuItem::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id, // Assign category_id
            'name' => 'Burger',
            'price' => 120.00,
        ]);
    }
}
