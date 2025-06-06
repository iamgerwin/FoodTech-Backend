<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => null,
            'order_id' => null,
            'driver_id' => null,
            'status' => $this->faker->randomElement(['pending', 'assigned', 'picked_up', 'delivered', 'failed']),
            'pickup_address' => $this->faker->address(),
            'pickup_latitude' => $this->faker->latitude(),
            'pickup_longitude' => $this->faker->longitude(),
            'delivery_address' => $this->faker->address(),
            'delivery_latitude' => $this->faker->latitude(),
            'delivery_longitude' => $this->faker->longitude(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'pickup_estimated_at' => $this->faker->dateTimeBetween('now', '+1 hour'),
            'picked_up_at' => $this->faker->optional()->dateTimeBetween('now', '+2 hours'),
            'delivery_estimated_at' => $this->faker->dateTimeBetween('now', '+2 hours'),
            'delivered_at' => $this->faker->optional()->dateTimeBetween('now', '+3 hours'),
            'delivery_fee' => $this->faker->randomFloat(2, 2, 20),
            'driver_earning' => $this->faker->randomFloat(2, 1, 15),
            'platform_commission' => $this->faker->randomFloat(2, 0.5, 5),
            'delivery_instructions' => $this->faker->sentence(),
            'proof_of_delivery' => $this->faker->optional()->imageUrl(),
            'failure_reason' => $this->faker->optional()->sentence(),
        ];
    }
}
