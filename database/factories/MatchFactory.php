<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        $statuses = ['upcoming', 'live', 'finished'];
        $status = fake()->randomElement($statuses);

        return [
            'tournament_id' => Tournament::factory(),
            'team_a_id' => Team::factory(),
            'team_b_id' => Team::factory(),
            'venue_id' => Venue::factory(),
            'scheduled_at' => fake()->dateTimeBetween('-1 week', '+2 weeks'),
            'status' => $status,
            'score_a' => $status === 'finished' ? fake()->numberBetween(0, 3) : ($status === 'live' ? fake()->numberBetween(0, 2) : 0),
            'score_b' => $status === 'finished' ? fake()->numberBetween(0, 3) : ($status === 'live' ? fake()->numberBetween(0, 2) : 0),
            'stream_url' => fake()->optional(0.8)->url(),
            'current_map' => $status === 'live' ? fake()->randomElement(['Ascent', 'Haven', 'Bind', 'Mirage', 'Dust2', 'Land of Dawn']) : null,
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
            'status' => 'upcoming',
            'scheduled_at' => now()->addHours(3),
        ]);
    }

    public function live(): static
    {
        return $this->state(fn () => [
            'status' => 'live',
            'scheduled_at' => now()->subMinutes(45),
            'score_a' => fake()->numberBetween(1, 2),
            'score_b' => fake()->numberBetween(0, 2),
            'current_map' => fake()->randomElement(['Ascent', 'Haven', 'Bind', 'Mirage', 'Dust2']),
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn () => [
            'status' => 'finished',
            'scheduled_at' => fake()->dateTimeBetween('-2 weeks', '-1 day'),
            'score_a' => fake()->numberBetween(1, 3),
            'score_b' => fake()->numberBetween(0, 3),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'status' => 'upcoming',
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+2 weeks'),
            'score_a' => 0,
            'score_b' => 0,
        ]);
    }
}
