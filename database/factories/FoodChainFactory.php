<?php

namespace Database\Factories;

use App\Models\Tenant; // Added
use App\Models\User;   // Added
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodChain>
 */
class FoodChainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenantId = $this->faker->boolean(50) && Tenant::count() > 0
                        ? Tenant::inRandomOrder()->first()->id
                        : Tenant::factory()->create()->id;

        $ownerId = User::factory()->create(['tenant_id' => $tenantId])->id;

        return [
            'tenant_id' => $tenantId,
            'owner_id' => $ownerId,
            'name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'logo' => $this->faker->optional()->imageUrl(),
            'contact_email' => $this->faker->companyEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
            'business_license' => strtoupper($this->faker->bothify('BL#######')),
            'tax_id' => strtoupper($this->faker->bothify('TAX#######')),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
