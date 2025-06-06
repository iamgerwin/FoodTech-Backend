<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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

        $customerId = User::factory()->create(['tenant_id' => $tenantId, 'user_type' => 'customer'])->id;
        $restaurant = Restaurant::factory()->create(['tenant_id' => $tenantId]);
        
        // Assuming RestaurantBranchFactory and CustomerAddressFactory exist and can accept tenant_id and relevant parent IDs
        // If they don't, these lines might need adjustment or those factories need to be created/updated.
        $branchId = RestaurantBranch::factory()->create([
            'tenant_id' => $tenantId, 
            'restaurant_id' => $restaurant->id
        ])->id;
        
        $deliveryAddressId = CustomerAddress::factory()->create([
            'tenant_id' => $tenantId, 
            'customer_id' => $customerId
        ])->id;

        return [
            'tenant_id' => $tenantId,
            'order_number' => strtoupper($this->faker->bothify('ORD#######')),
            'customer_id' => $customerId,
            'restaurant_id' => $restaurant->id,
            'branch_id' => $branchId,
            'delivery_address_id' => $deliveryAddressId,
            'status' => $this->faker->randomElement(['pending','confirmed','preparing','ready','dispatched','delivered','cancelled']),
            'order_type' => $this->faker->randomElement(['delivery','pickup']),
            'payment_status' => $this->faker->randomElement(['pending','paid','failed','refunded']),
            'payment_method' => $this->faker->randomElement(['cash','card','e-wallet']),
            'subtotal' => $this->faker->randomFloat(2, 10, 150),
            'tax_amount' => $this->faker->randomFloat(2, 0, 15),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 10),
            'service_charge' => $this->faker->randomFloat(2, 0, 5),
            'discount_amount' => $this->faker->randomFloat(2, 0, 20),
            'total_amount' => $this->faker->randomFloat(2, 10, 200),
            'estimated_prep_time' => $this->faker->numberBetween(10, 60),
            'estimated_delivery_time' => $this->faker->dateTimeBetween('+30 minutes', '+2 hours'),
            'placed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'confirmed_at' => $this->faker->optional()->dateTimeBetween('now', '+1 hour'),
            'ready_at' => $this->faker->optional()->dateTimeBetween('now', '+2 hours'),
            'dispatched_at' => $this->faker->optional()->dateTimeBetween('now', '+3 hours'),
            'delivered_at' => $this->faker->optional()->dateTimeBetween('now', '+4 hours'),
        ];
    }
}

