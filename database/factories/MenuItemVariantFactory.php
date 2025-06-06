<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItemVariant>
 */
class MenuItemVariantFactory extends Factory
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
            'menu_item_id' => null,
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['size', 'topping', 'side']),
            'price_modifier' => $this->faker->randomFloat(2, 0, 10),
            'is_required' => $this->faker->boolean(20),
            'is_available' => $this->faker->boolean(90),
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
