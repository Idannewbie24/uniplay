<?php

namespace Database\Seeders;

use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class StandingSeeder extends Seeder
{
    public function run(): void
    {
        $tournament = Tournament::where('name', 'VCT University Finals 2026')->first();

        if (!$tournament) {
            return;
        }

        $standings = [
            ['tag' => 'SEN', 'wins' => 4, 'losses' => 1, 'points' => 12, 'rank' => 1],
            ['tag' => 'FNC', 'wins' => 3, 'losses' => 2, 'points' => 9, 'rank' => 2],
            ['tag' => 'NAVI', 'wins' => 3, 'losses' => 2, 'points' => 9, 'rank' => 3],
            ['tag' => 'TL', 'wins' => 2, 'losses' => 3, 'points' => 6, 'rank' => 4],
            ['tag' => 'TSM', 'wins' => 1, 'losses' => 4, 'points' => 3, 'rank' => 5],
        ];

        foreach ($standings as $standing) {
            $team = Team::where('tag', $standing['tag'])->first();

            if ($team) {
                Standing::create([
                    'tournament_id' => $tournament->id,
                    'team_id' => $team->id,
                    'wins' => $standing['wins'],
                    'losses' => $standing['losses'],
                    'points' => $standing['points'],
                    'rank' => $standing['rank'],
                ]);
            }
        }
    }
}
