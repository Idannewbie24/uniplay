<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            [
                'name' => 'Mobile Legends: Bang Bang',
                'slug' => 'mobile-legends',
                'publisher' => 'Moonton',
                'icon' => 'games/mobile-legends-icon.png',
                'banner' => 'games/mobile-legends-banner.jpg',
                'category' => 'mobile',
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'publisher' => 'Garena',
                'icon' => 'games/free-fire-icon.png',
                'banner' => 'games/free-fire-banner.jpg',
                'category' => 'mobile',
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'publisher' => 'Riot Games',
                'icon' => 'games/valorant-icon.png',
                'banner' => 'games/valorant-banner.jpg',
                'category' => 'pc',
            ],
            [
                'name' => 'Counter-Strike 2',
                'slug' => 'cs2',
                'publisher' => 'Valve',
                'icon' => 'games/cs2-icon.png',
                'banner' => 'games/cs2-banner.jpg',
                'category' => 'pc',
            ],
            [
                'name' => 'EA FC 25',
                'slug' => 'ea-fc-25',
                'publisher' => 'Electronic Arts',
                'icon' => 'games/ea-fc-25-icon.png',
                'banner' => 'games/ea-fc-25-banner.jpg',
                'category' => 'console',
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
