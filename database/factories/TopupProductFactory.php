<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\TopupProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopupProductFactory extends Factory
{
    protected $model = TopupProduct::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'name' => fake()->words(2, true) . ' Top Up',
            'description' => fake()->sentence(10),
            'thumbnail' => 'topup/' . fake()->uuid() . '.png',
            'server_region' => fake()->randomElement(['SEA', 'ID', 'Global', 'Asia']),
            'fulfillment_method' => fake()->randomElement(['API Auto', 'Manual']),
            'support_hours' => '08:00 - 22:00 WIB',
            'is_official_partner' => fake()->boolean(70),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
        ];
    }
}
