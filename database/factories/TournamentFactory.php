<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory
{
    protected $model = Tournament::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true) . ' Championship',
            'game_id' => Game::factory(),
            'stage' => fake()->randomElement(['Group Stage', 'Quarterfinal', 'Semifinal', 'Grand Final']),
            'format' => fake()->randomElement(['BO3', 'BO5', 'BO7']),
            'prize_pool' => 'Rp ' . number_format(fake()->numberBetween(5000000, 50000000), 0, ',', '.'),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'status' => fake()->randomElement(['upcoming', 'ongoing', 'completed']),
        ];
    }
}
