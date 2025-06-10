<?php

namespace Database\Factories;

use App\Models\Tenant; // Added
use App\Models\User;   // Added
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenantId = $this->attributes['tenant_id']
            ?? (Tenant::count() > 0 ? Tenant::inRandomOrder()->first()->id : Tenant::factory()->create()->id);

        $userId = $this->attributes['user_id']
            ?? User::factory()->create([
                'tenant_id' => $tenantId,
                'user_type' => 'driver', // Assuming 'driver' is a valid user_type
            ])->id;

        return [
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'license_number' => strtoupper($this->faker->bothify('DL#######')),
            'license_expiry' => $this->faker->date('Y-m-d', '+2 years'),
            'vehicle_type' => $this->faker->randomElement(['car', 'motorcycle', 'bicycle']),
            'vehicle_plate' => strtoupper($this->faker->bothify('???###')),
            'vehicle_model' => $this->faker->word(),
            'is_verified' => $this->faker->boolean(80),
            'is_active' => $this->faker->boolean(90),
            'is_available' => $this->faker->boolean(70),
            'current_latitude' => $this->faker->latitude(),
            'current_longitude' => $this->faker->longitude(),
            'last_location_update' => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'rating' => $this->faker->randomFloat(2, 3, 5),
            'total_deliveries' => $this->faker->numberBetween(0, 1000),
            'total_earnings' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
