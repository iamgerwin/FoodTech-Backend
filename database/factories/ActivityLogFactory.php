<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjectType = $this->faker->randomElement([
            User::class,
            Order::class,
            Restaurant::class,
        ]);

        $subject = $subjectType::factory()->create();

        $causerType = $this->faker->randomElement([
            User::class,
            Driver::class,
        ]);

        $causer = $causerType::factory()->create();

        return [
            'log_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'subject_type' => $subjectType,
            'event' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'subject_id' => $subject->id,
            'causer_type' => $causerType,
            'causer_id' => $causer->id,
            'properties' => ['ip' => $this->faker->ipv4(), 'user_agent' => $this->faker->userAgent()],
            'batch_uuid' => $this->faker->uuid(),
        ];
    }
}
