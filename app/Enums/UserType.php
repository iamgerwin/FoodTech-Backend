<?php

namespace App\Enums;

enum UserType: string
{
    case Customer = 'customer';
    case RestaurantBranchManager = 'restaurant_branch_manager';
    case Admin = 'admin';
    case Driver = 'driver';
    case FoodChainManager = 'food_chain_manager';
    case CustomerService = 'customer_service';
    case RestaurantSales = 'restaurant_sales';

    public function label(): string
    {
        return match($this) {
            self::Customer => 'Customer',
            self::RestaurantBranchManager => 'Restaurant Branch Manager',
            self::Admin => 'Admin',
            self::Driver => 'Driver',
            self::FoodChainManager => 'Food Chain Manager',
            self::CustomerService => 'Customer Service',
            self::RestaurantSales => 'Restaurant Sales',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Customer => 'End user who orders food',
            self::RestaurantBranchManager => 'Manages a specific restaurant branch',
            self::Admin => 'Platform administrator',
            self::Driver => 'Delivers orders to customers',
            self::FoodChainManager => 'Manages multiple branches of a food chain',
            self::CustomerService => 'Handles customer support',
            self::RestaurantSales => 'Handles restaurant sales operations',
        };
    }

    public function key(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return array_map(fn($type) => [
            'key' => $type->key(),
            'label' => $type->label(),
            'description' => $type->description()
        ], self::cases());
    }
}
