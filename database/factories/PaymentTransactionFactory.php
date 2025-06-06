<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 1,
            'order_id' => 1,
            'transaction_id' => strtoupper($this->faker->bothify('TXN#######')),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'e-wallet']),
            'payment_provider' => $this->faker->randomElement(['stripe', 'paypal', 'adyen', 'manual']),
            'provider_transaction_id' => strtoupper($this->faker->bothify('PTXN#######')),
            'amount' => $this->faker->randomFloat(2, 10, 200),
            'currency' => $this->faker->currencyCode(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'gateway_response' => ['response_code' => $this->faker->randomElement(['00', '01', '05']), 'message' => $this->faker->sentence()],
            'processed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
