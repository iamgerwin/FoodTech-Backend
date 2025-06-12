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
            'payment_type' => $this->faker->randomElement(['cash', 'card', 'e-wallet']),
            'payment_subtype' => function (array $attributes) {
                if ($attributes['payment_type'] === 'e-wallet') {
                    return $this->faker->randomElement(['gcash', 'maya', 'coins.ph']);
                }
                if ($attributes['payment_type'] === 'card') {
                    return $this->faker->randomElement(['debit', 'credit']);
                }
                return null;
            },
            'payment_details' => function (array $attributes) {
                $details = [];
                if ($attributes['payment_type'] === 'e-wallet') {
                    $details['account_number'] = $this->faker->phoneNumber();
                    $details['account_name'] = $this->faker->name();
                } elseif ($attributes['payment_type'] === 'card') {
                    $details['last4'] = $this->faker->randomNumber(4, true);
                    $details['brand'] = $this->faker->creditCardType();
                    $details['exp_month'] = $this->faker->month();
                    $details['exp_year'] = $this->faker->year('+5 years');
                }
                return $details;
            },
            'payment_provider' => function (array $attributes) {
                if ($attributes['payment_type'] === 'e-wallet') {
                    return $attributes['payment_subtype'];
                }
                return $this->faker->randomElement(['stripe', 'paypal', 'adyen', 'manual']);
            },
            'provider_transaction_id' => strtoupper($this->faker->bothify('PTXN#######')),
            'amount' => $this->faker->randomFloat(2, 10, 200),
            'currency' => $this->faker->currencyCode(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'gateway_response' => [
                'response_code' => $this->faker->randomElement(['00', '01', '05']), 
                'message' => $this->faker->sentence()
            ],
            'processed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
