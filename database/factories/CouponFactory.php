<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
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
            'restaurant_id' => null,
            'code' => strtoupper($this->faker->bothify('COUPON###')),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['percent', 'fixed']),
            'value' => $this->faker->randomFloat(2, 5, 50),
            'minimum_order_amount' => $this->faker->randomFloat(2, 10, 100),
            'maximum_discount_amount' => $this->faker->randomFloat(2, 10, 100),
            'usage_limit' => $this->faker->numberBetween(1, 100),
            'usage_limit_per_customer' => $this->faker->numberBetween(1, 10),
            'current_usage' => $this->faker->numberBetween(0, 50),
            'starts_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
