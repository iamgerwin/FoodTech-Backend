<?php

namespace Database\Factories;

use App\Models\Restaurant;       // Added
use App\Models\Tenant;   // Added
use App\Models\User;         // Added
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RestaurantBranch>
 */
class RestaurantBranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure tenant_id and restaurant_id are provided or created.
        // This factory will typically be called with these IDs already set by a parent factory (e.g., OrderFactory).
        // If not, it creates them, which might lead to orphaned data if not handled carefully in tests/seeders.

        $tenantId = $this->attributes['tenant_id']
            ?? (Tenant::count() > 0 ? Tenant::inRandomOrder()->first()->id : Tenant::factory()->create()->id);

        $restaurantId = $this->attributes['restaurant_id']
            ?? Restaurant::factory()->create(['tenant_id' => $tenantId])->id;

        $managerId = User::factory()->create([
            'tenant_id' => $tenantId,
            'user_type' => 'manager', // Assuming 'manager' is a valid user_type
        ])->id;

        return [
            'tenant_id' => $tenantId,
            'restaurant_id' => $restaurantId,
            'manager_id' => $managerId,
            'name' => $this->faker->company().' Branch',
            'address' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'is_active' => $this->faker->boolean(90),
            'accepts_orders' => $this->faker->boolean(90),
            'delivery_radius_km' => $this->faker->randomFloat(1, 1, 15),
            'opens_at' => $this->faker->time('H:i:s'),
            'closes_at' => $this->faker->time('H:i:s'),
        ];
    }
}
