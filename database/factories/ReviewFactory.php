<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
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
            'reviewer_id' => null,
            'reviewee_type' => $this->faker->randomElement(['App\\Models\\Restaurant', 'App\\Models\\Driver']),
            'reviewee_id' => null,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(),
            'is_anonymous' => $this->faker->boolean(10),
            'is_approved' => $this->faker->boolean(90),
        ];
    }
}
