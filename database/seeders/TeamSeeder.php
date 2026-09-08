<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name' => 'Natus Vincere', 'tag' => 'NAVI', 'seed_rank' => 1],
            ['name' => 'Sentinels', 'tag' => 'SEN', 'seed_rank' => 2],
            ['name' => 'Team SoloMid', 'tag' => 'TSM', 'seed_rank' => 3],
            ['name' => 'Fnatic', 'tag' => 'FNC', 'seed_rank' => 4],
            ['name' => 'EVOS Legends', 'tag' => 'EVOS', 'seed_rank' => 5],
            ['name' => 'ONIC Esports', 'tag' => 'ONIC', 'seed_rank' => 6],
            ['name' => 'Bigetron Alpha', 'tag' => 'BTR', 'seed_rank' => 7],
            ['name' => 'Team Liquid', 'tag' => 'TL', 'seed_rank' => 8],
            ['name' => 'RRQ Hoshi', 'tag' => 'RRQ', 'seed_rank' => 9],
            ['name' => 'Alter Ego', 'tag' => 'AE', 'seed_rank' => 10],
        ];

        foreach ($teams as $team) {
            Team::create([
                ...$team,
                'logo' => 'teams/' . strtolower($team['tag']) . '-logo.png',
            ]);
        }
    }
}
