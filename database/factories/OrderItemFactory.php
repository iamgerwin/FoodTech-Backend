<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
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
            'menu_item_id' => \App\Models\MenuItem::inRandomOrder()->first()?->id,
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->randomFloat(2, 5, 50),
            'total_price' => $this->faker->randomFloat(2, 5, 250),
            'special_instructions' => $this->faker->optional()->sentence(),
        ];
    }
}
