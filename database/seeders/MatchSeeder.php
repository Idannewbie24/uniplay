<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::all();
        $tournaments = Tournament::all();
        $venues = Venue::all();

        $matches = [
            [
                'tournament_id' => $tournaments->firstWhere('name', 'VCT University Finals 2026')->id,
                'team_a_id' => $teams->where('tag', 'SEN')->first()->id,
                'team_b_id' => $teams->where('tag', 'FNC')->first()->id,
                'venue_id' => $venues->where('city', 'Jakarta')->first()->id,
                'scheduled_at' => now()->addHours(3),
                'status' => 'upcoming',
                'score_a' => 0,
                'score_b' => 0,
                'is_featured' => true,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'VCT University Finals 2026')->id,
                'team_a_id' => $teams->where('tag', 'NAVI')->first()->id,
                'team_b_id' => $teams->where('tag', 'TL')->first()->id,
                'venue_id' => $venues->where('city', 'Jakarta')->first()->id,
                'scheduled_at' => now()->subMinutes(45),
                'status' => 'live',
                'score_a' => 2,
                'score_b' => 1,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'VCT University Finals 2026')->id,
                'team_a_id' => $teams->where('tag', 'TSM')->first()->id,
                'team_b_id' => $teams->where('tag', 'SEN')->first()->id,
                'venue_id' => $venues->where('city', 'Jakarta')->first()->id,
                'scheduled_at' => now()->subDays(1),
                'status' => 'finished',
                'score_a' => 3,
                'score_b' => 2,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'VCT University Finals 2026')->id,
                'team_a_id' => $teams->where('tag', 'FNC')->first()->id,
                'team_b_id' => $teams->where('tag', 'NAVI')->first()->id,
                'venue_id' => $venues->where('city', 'Jakarta')->first()->id,
                'scheduled_at' => now()->subDays(3),
                'status' => 'finished',
                'score_a' => 1,
                'score_b' => 3,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'MLBB Campus League Season 3')->id,
                'team_a_id' => $teams->where('tag', 'EVOS')->first()->id,
                'team_b_id' => $teams->where('tag', 'RRQ')->first()->id,
                'venue_id' => $venues->where('city', 'Surabaya')->first()->id,
                'scheduled_at' => now()->addDays(5),
                'status' => 'upcoming',
                'score_a' => 0,
                'score_b' => 0,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'MLBB Campus League Season 3')->id,
                'team_a_id' => $teams->where('tag', 'ONIC')->first()->id,
                'team_b_id' => $teams->where('tag', 'AE')->first()->id,
                'venue_id' => $venues->where('city', 'Surabaya')->first()->id,
                'scheduled_at' => now()->addDays(7),
                'status' => 'upcoming',
                'score_a' => 0,
                'score_b' => 0,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'CS2 Collegiate Cup')->id,
                'team_a_id' => $teams->where('tag', 'BTR')->first()->id,
                'team_b_id' => $teams->where('tag', 'TL')->first()->id,
                'venue_id' => $venues->where('city', 'Bandung')->first()->id,
                'scheduled_at' => now()->addDays(14),
                'status' => 'upcoming',
                'score_a' => 0,
                'score_b' => 0,
                'is_featured' => false,
            ],
            [
                'tournament_id' => $tournaments->firstWhere('name', 'CS2 Collegiate Cup')->id,
                'team_a_id' => $teams->where('tag', 'NAVI')->first()->id,
                'team_b_id' => $teams->where('tag', 'TSM')->first()->id,
                'venue_id' => $venues->where('city', 'Medan')->first()->id,
                'scheduled_at' => now()->subDays(2),
                'status' => 'finished',
                'score_a' => 2,
                'score_b' => 0,
                'is_featured' => false,
            ],
        ];

        foreach ($matches as $match) {
            GameMatch::create($match);
        }
    }
}