<?php
namespace App\Services;

use App\Models\RestaurantBranch;

class BranchMenuService
{
    public static function getBranchMenu($branchId)
    {
        $branch = RestaurantBranch::with([
            'branchMenuItemOverrides.menuItem',
            'branchMenuItemVariantOverrides.menuItemVariant',
        ])->findOrFail($branchId);

        $menu = [];
        foreach ($branch->branchMenuItemOverrides as $override) {
            $menu[] = [
                'name' => $override->effective_name,
                'price' => $override->effective_price,
                'description' => $override->custom_description ?? optional($override->menuItem)->description,
                'is_overridden' => $override->custom_name || $override->custom_price || $override->custom_description,
            ];
        }
        // You can add variants similarly
        return $menu;
    }
}
