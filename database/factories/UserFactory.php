<?php

namespace Database\Factories;

use App\Models\Tenant; // Added
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(), // Changed
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'), // Corrected bcrypt and static password usage
            'phone' => $this->faker->phoneNumber(),
            'avatar' => $this->faker->optional()->imageUrl(),
            'is_active' => $this->faker->boolean(90),
            'user_type' => $this->faker->randomElement(['customer', 'driver', 'manager', 'admin', 'developer']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
