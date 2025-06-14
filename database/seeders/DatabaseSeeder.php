<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed 2 tenants
        $tenants = \App\Models\Tenant::factory(2)->create();

        // For each tenant, seed users, food chains, restaurants, and related data
        $tenants->each(function ($tenant) {
            // Users with customer profile and addresses (using has/hasMany)
            $users = \App\Models\User::factory()
                ->count(10)
                ->has(
                    \App\Models\CustomerProfile::factory()
                        ->state(function (array $attributes, \App\Models\User $user) use ($tenant) {
                            return [
                                'tenant_id' => $tenant->id,
                                'user_id' => $user->id,
                            ];
                        }),
                    'customerProfile'
                )
                ->has(
                    \App\Models\CustomerAddress::factory()->count(2)
                        ->state(function (array $attributes, \App\Models\User $user) use ($tenant) {
                            return [
                                'tenant_id' => $tenant->id,
                                'customer_id' => $user->id,
                            ];
                        }),
                    'addresses'
                )
                ->create(['tenant_id' => $tenant->id]);

            // Drivers: create a User for each Driver, then assign the user's id as user_id
            $drivers = collect();
            for ($i = 0; $i < 3; $i++) {
                $driverUser = \App\Models\User::factory()->create([
                    'tenant_id' => $tenant->id,
                    'user_type' => 'driver',
                ]);
                $drivers->push(
                    \App\Models\Driver::factory()->create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $driverUser->id,
                    ])
                );
            }
            $managers = \App\Models\User::factory(2)->create(['tenant_id' => $tenant->id, 'user_type' => 'manager']);

            // Food Chains: explicitly set tenant_id and owner_id (UUIDs)
            $foodChains = collect();
            for ($i = 0; $i < 2; $i++) {
                $foodChains->push(
                    \App\Models\FoodChain::factory()->create([
                        'tenant_id' => $tenant->id,
                        'owner_id' => $users->random()->id,
                    ])
                );
            }

            // Restaurants for each food chain
            $foodChains->each(function ($chain) use ($tenant, $users) {
                $restaurants = collect();
                for ($i = 0; $i < 2; $i++) {
                    // DEBUG: Log chain id and intended restaurant data
                    info('Seeding Restaurant', [
                        'tenant_id' => $tenant->id,
                    ]);
                    $restaurants->push(
                        \App\Models\Restaurant::factory()->create([
                            'tenant_id' => $tenant->id,
                        ])
                    );
                }
                $restaurants->each(function ($restaurant) use ($tenant, $users) {
                    // Branches
                    $branches = \App\Models\RestaurantBranch::factory(2)->create([
                        'tenant_id' => $tenant->id,
                        'restaurant_id' => $restaurant->id,
                        'manager_id' => $users->random()->id,
                    ]);
                    // Menu categories with menu items and variants using has/hasMany
                    $categories = collect();
                    for ($i = 0; $i < 2; $i++) {
                        $categories->push(
                            \App\Models\MenuCategory::factory()
                                ->has(
                                    \App\Models\MenuItem::factory()
                                        ->count(5)
                                        ->has(
                                            \App\Models\MenuItemVariant::factory()
                                                ->count(2)
                                                ->state(function (array $attributes, \App\Models\MenuItem $menuItem) use ($tenant) {
                                                    return [
                                                        'tenant_id' => $tenant->id,
                                                        'menu_item_id' => $menuItem->id,
                                                    ];
                                                }),
                                            'variants'
                                        )
                                        ->state(['tenant_id' => $tenant->id, 'restaurant_id' => $restaurant->id, 'category_id' => null]),
                                    'menuItems'
                                )
                                ->create([
                                    'tenant_id' => $tenant->id,
                                    'restaurant_id' => $restaurant->id,
                                ])
                        );
                    }
                });
            });

            // Orders for random users, with items and item variants using has/hasMany
            $orders = \App\Models\Order::factory()
                ->count(10)
                ->has(
                    \App\Models\OrderItem::factory()
                        ->count(2)
                        ->has(
                            \App\Models\OrderItemVariant::factory()
                                ->count(1)
                                ->state(function (array $attributes, \App\Models\OrderItem $orderItem) use ($tenant) {
                                    return [
                                        'tenant_id' => $tenant->id,
                                        'order_item_id' => $orderItem->id,
                                    ];
                                }),
                            'variants'
                        )
                        ->state(['tenant_id' => $tenant->id]),
                    'items'
                )
                ->create([
                    'tenant_id' => $tenant->id,
                    'customer_id' => $users->random()->id,
                    'restaurant_id' => optional(\App\Models\Restaurant::inRandomOrder()->first())->id,
                    'branch_id' => optional(\App\Models\RestaurantBranch::inRandomOrder()->first())->id,
                    'delivery_address_id' => optional(\App\Models\CustomerAddress::inRandomOrder()->first())->id,
                ]);

            // Payment, delivery, and review for each order (manual for complex dependencies)
            $orders->each(function ($order) use ($tenant) {
                \App\Models\PaymentTransaction::factory()->create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                ]);
                \App\Models\Delivery::factory()->create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'driver_id' => \App\Models\Driver::inRandomOrder()->first()->id ?? null,
                ]);
                \App\Models\Review::factory()->create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'reviewer_id' => $order->customer_id,
                    'reviewee_type' => 'App\\Models\\Restaurant',
                    'reviewee_id' => $order->restaurant_id,
                ]);
            });

            // Coupons and usages
            $coupons = \App\Models\Coupon::factory(2)->create([
                'tenant_id' => $tenant->id,
                'restaurant_id' => \App\Models\Restaurant::inRandomOrder()->first()->id ?? null,
            ]);
            $coupons->each(function ($coupon) use ($tenant, $users) {
                \App\Models\CouponUsage::factory(2)->create([
                    'tenant_id' => $tenant->id,
                    'coupon_id' => $coupon->id,
                    'order_id' => \App\Models\Order::inRandomOrder()->first()->id ?? null,
                    'customer_id' => $users->random()->id,
                ]);
            });

            // Notifications
            $users->each(function ($user) use ($tenant) {
                \App\Models\Notification::factory(2)->create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                ]);
            });

            // Activity logs
            \App\Models\ActivityLog::factory(5)->create();
        });

        // Keep the test user creation for admin/dev login
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tenant_id' => $tenants->first()->id,
            'user_type' => 'developer',
        ]);
    }
}
