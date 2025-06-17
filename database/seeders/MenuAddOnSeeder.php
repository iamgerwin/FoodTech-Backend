<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuAddOn;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use Illuminate\Support\Str;

class MenuAddOnSeeder extends Seeder
{
    public function run(): void
    {
        // Create some sample add-ons
        $addons = [
            [
                'name' => 'Extra Cheese',
                'price' => 20,
                'is_available' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Spicy Sauce',
                'price' => 15,
                'is_available' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Bacon Bits',
                'price' => 30,
                'is_available' => true,
                'sort_order' => 3,
            ],
        ];

        $menuAddOns = collect();
        foreach ($addons as $addon) {
            $menuAddOn = MenuAddOn::create(array_merge($addon, [
                'id' => (string) Str::uuid(),
                'tenant_id' => null, // Set as needed
            ]));
            $menuAddOns->push($menuAddOn);
        }

        // Attach add-ons to random menu items
        $menuItems = MenuItem::inRandomOrder()->take(5)->get();
        foreach ($menuItems as $menuItem) {
            $menuItem->menuAddOns()->syncWithoutDetaching($menuAddOns->pluck('id')->toArray());
        }

        // Optionally, attach add-ons to MenuItemVariants if you have a similar relation
        // Example: If MenuItemVariant has menuAddOns relationship
        if (method_exists(MenuItemVariant::class, 'menuAddOns')) {
            $menuItemVariants = MenuItemVariant::inRandomOrder()->take(5)->get();
            foreach ($menuItemVariants as $variant) {
                $variant->menuAddOns()->syncWithoutDetaching($menuAddOns->pluck('id')->toArray());
            }
        }
    }
}
