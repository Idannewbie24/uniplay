<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        Tournament::create([
            'name' => 'VCT University Finals 2026',
            'game_id' => Game::where('slug', 'valorant')->first()->id,
            'stage' => 'Grand Final',
            'format' => 'BO5',
            'prize_pool' => 'Rp 50.000.000',
            'start_date' => now()->subWeek(),
            'end_date' => now()->addWeek(),
            'status' => 'ongoing',
        ]);

        Tournament::create([
            'name' => 'MLBB Campus League Season 3',
            'game_id' => Game::where('slug', 'mobile-legends')->first()->id,
            'stage' => 'Semifinal',
            'format' => 'BO3',
            'prize_pool' => 'Rp 25.000.000',
            'start_date' => now()->addDays(3),
            'end_date' => now()->addWeeks(2),
            'status' => 'upcoming',
        ]);

        Tournament::create([
            'name' => 'CS2 Collegiate Cup',
            'game_id' => Game::where('slug', 'cs2')->first()->id,
            'stage' => 'Group Stage',
            'format' => 'BO3',
            'prize_pool' => 'Rp 35.000.000',
            'start_date' => now()->addWeeks(2),
            'end_date' => now()->addMonths(1),
            'status' => 'upcoming',
        ]);
    }
}
