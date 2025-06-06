<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // These should be set explicitly when calling the factory, e.g. MenuItem::factory()->create(['tenant_id' => $tenant->id, 'restaurant_id' => $restaurant->id, 'category_id' => $category->id])
            'tenant_id' => null,
            'restaurant_id' => null,
            'category_id' => null,
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->optional()->imageUrl(),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'discounted_price' => $this->faker->optional()->randomFloat(2, 3, 45),
            'preparation_time' => $this->faker->numberBetween(5, 60),
            'calories' => $this->faker->numberBetween(100, 1200),
            'ingredients' => implode(', ', $this->faker->words(5)),
            'allergens' => $this->faker->optional()->word(),
            'is_vegetarian' => $this->faker->boolean(30),
            'is_vegan' => $this->faker->boolean(10),
            'is_gluten_free' => $this->faker->boolean(20),
            'is_spicy' => $this->faker->boolean(25),
            'spice_level' => $this->faker->optional()->numberBetween(1, 5),
            'is_available' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(10),
            'sort_order' => $this->faker->numberBetween(1, 20),
        ];
    }
}
