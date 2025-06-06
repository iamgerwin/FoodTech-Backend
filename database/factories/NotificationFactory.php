<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
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
            'user_id' => 1,
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['info', 'order', 'promo', 'system']),
            'data' => ['action' => $this->faker->word()],
            'is_read' => $this->faker->boolean(20),
            'sent_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'read_at' => $this->faker->optional()->dateTimeBetween('now', '+1 week'),
        ];
    }
}
