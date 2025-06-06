<?php

namespace Database\Factories;

use App\Models\Tenant;     // Added
use App\Models\FoodChain;  // Added
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenantId = $this->faker->boolean(50) && Tenant::count() > 0 
                        ? Tenant::inRandomOrder()->first()->id 
                        : Tenant::factory()->create()->id;

        $foodChainId = FoodChain::factory()->create(['tenant_id' => $tenantId])->id;

        return [
            'tenant_id' => $tenantId,
            'food_chain_id' => $foodChainId,
            'name' => $this->faker->company(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'logo' => $this->faker->optional()->imageUrl(),
            'banner_image' => $this->faker->optional()->imageUrl(),
            'cuisine_type' => $this->faker->randomElement(['Italian', 'Chinese', 'Indian', 'American', 'Japanese', 'Mexican']),
            'average_prep_time' => $this->faker->numberBetween(10, 60),
            'minimum_order_amount' => $this->faker->randomFloat(2, 10, 50),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 10),
            'service_charge_percentage' => $this->faker->randomFloat(2, 0, 15),
            'is_active' => $this->faker->boolean(90),
            'opens_at' => $this->faker->time('H:i:s'),
            'closes_at' => $this->faker->time('H:i:s'),
        ];
    }
}

