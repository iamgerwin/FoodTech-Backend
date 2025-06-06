<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponUsage>
 */
class CouponUsageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'coupon_id' => 1,
            'order_id' => 1,
            'customer_id' => 1,
            'discount_amount' => $this->faker->randomFloat(2, 1, 20),
            'used_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
