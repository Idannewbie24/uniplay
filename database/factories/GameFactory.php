<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        $games = [
            ['name' => 'Mobile Legends: Bang Bang', 'slug' => 'mobile-legends', 'publisher' => 'Moonton', 'category' => 'mobile'],
            ['name' => 'Free Fire', 'slug' => 'free-fire', 'publisher' => 'Garena', 'category' => 'mobile'],
            ['name' => 'Valorant', 'slug' => 'valorant', 'publisher' => 'Riot Games', 'category' => 'pc'],
            ['name' => 'Counter-Strike 2', 'slug' => 'cs2', 'publisher' => 'Valve', 'category' => 'pc'],
            ['name' => 'EA FC 25', 'slug' => 'ea-fc-25', 'publisher' => 'Electronic Arts', 'category' => 'console'],
        ];

        $game = $this->faker->unique()->randomElement($games);

        return [
            'name' => $game['name'],
            'slug' => $game['slug'],
            'publisher' => $game['publisher'],
            'icon' => 'games/' . $game['slug'] . '-icon.png',
            'banner' => 'games/' . $game['slug'] . '-banner.jpg',
            'category' => $game['category'],
        ];
    }
}
