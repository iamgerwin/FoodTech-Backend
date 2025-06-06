<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuCategory>
 */
class MenuCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // These should be set explicitly when calling the factory, e.g. MenuCategory::factory()->create(['tenant_id' => $tenant->id, 'restaurant_id' => $restaurant->id])
            'tenant_id' => null,
            'restaurant_id' => null,
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->optional()->imageUrl(),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
