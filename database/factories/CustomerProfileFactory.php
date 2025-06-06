<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerProfile>
 */
class CustomerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // These should be set explicitly when calling the factory, e.g. CustomerProfile::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $user->id])
            'tenant_id' => null,
            'user_id' => null,
            'date_of_birth' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'loyalty_points' => $this->faker->numberBetween(0, 1000),
            'total_orders' => $this->faker->numberBetween(0, 100),
            'total_spent' => $this->faker->randomFloat(2, 0, 10000),
            'preferred_payment_method' => $this->faker->randomElement(['cash', 'card', 'e-wallet']),
            'dietary_preferences' => $this->faker->words(2, true),
        ];
    }
}
