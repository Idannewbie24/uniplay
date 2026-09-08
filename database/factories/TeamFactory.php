<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'tag' => strtoupper(fake()->lexify('????')),
            'logo' => 'teams/' . fake()->uuid() . '.png',
            'seed_rank' => fake()->numberBetween(1, 10),
        ];
    }
}
