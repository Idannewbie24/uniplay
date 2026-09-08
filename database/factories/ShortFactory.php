<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Short;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShortFactory extends Factory
{
    protected $model = Short::class;

    public function definition(): array
    {
        $titles = [
            'Incredible Clutch 1v4 in Round 25',
            'Team Wipe in 8 Seconds Flat',
            'Ace with the Operator - Grand Final',
            'Best Squad Wipe of the Tournament',
            'Insane Comeback Round 30',
            'Top 5 Plays of the Week',
            'Match Point - Buzzer Beater',
            'MVP Highlight Reel',
        ];

        $tags = ['clutch', 'ace', 'mvp', 'highlight', 'teamfight', 'comEBback', 'topplay'];

        return [
            'match_id' => GameMatch::factory(),
            'title' => fake()->randomElement($titles),
            'video_url' => 'https://youtube.com/shorts/' . fake()->uuid(),
            'thumbnail' => 'shorts/' . fake()->uuid() . '.jpg',
            'duration_seconds' => fake()->numberBetween(15, 120),
            'views_count' => fake()->numberBetween(500, 250000),
            'creator_name' => fake()->optional(0.7)->userName(),
            'category_tag' => fake()->randomElement($tags),
        ];
    }
}
