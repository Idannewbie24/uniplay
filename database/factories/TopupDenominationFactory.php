<?php

namespace Database\Factories;

use App\Models\TopupDenomination;
use App\Models\TopupProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopupDenominationFactory extends Factory
{
    protected $model = TopupDenomination::class;

    public function definition(): array
    {
        $base = fake()->randomElement([56, 85, 172, 257, 344, 568, 853, 1160, 2010, 3025]);
        $bonus = fake()->numberBetween(0, (int) ($base * 0.1));
        $price = $base * fake()->randomFloat(2, 0.05, 0.09);

        return [
            'topup_product_id' => TopupProduct::factory(),
            'label' => $base . ' Diamonds',
            'bonus_amount' => $bonus,
            'price' => round($price, 2),
            'type' => fake()->randomElement(['diamonds', 'weekly_pass', 'points']),
        ];
    }
}
