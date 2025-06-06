<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItemVariant>
 */
class OrderItemVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => null, // Should be set by the seeder relationship
            'order_item_id' => null, // Should be set by the seeder relationship
            'variant_id' => \App\Models\MenuItemVariant::inRandomOrder()->first()?->id,
            'name' => $this->faker->word(),
            'price_modifier' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
