<?php

namespace Database\Factories;

use App\Models\Tenant; // Added
use App\Models\User;   // Added
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAddress>
 */
class CustomerAddressFactory extends Factory
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

        $customerId = $this->attributes['customer_id'] 
            ?? User::factory()->create([
                'tenant_id' => $tenantId, 
                'user_type' => 'customer' // Assuming 'customer' is a valid user_type
            ])->id;

        return [
            'tenant_id' => $tenantId,
            'customer_id' => $customerId,
            'label' => $this->faker->randomElement(['Home', 'Work', 'Other']),
            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'delivery_instructions' => $this->faker->sentence(),
            'is_default' => $this->faker->boolean(30),
        ];
    }
}

