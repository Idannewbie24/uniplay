<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            GameSeeder::class,
            TeamSeeder::class,
            TournamentSeeder::class,
            VenueSeeder::class,
            MatchSeeder::class,
            StandingSeeder::class,
            TopupSeeder::class,
            TicketSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
