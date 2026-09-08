<?php

namespace Database\Seeders;

use App\Models\Venue;
use App\Models\VenueZone;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Jakarta Convention Center',
                'address' => 'Jl. Gatot Subroto No.1, Senayan, Kec. Kby. Baru',
                'city' => 'Jakarta',
                'capacity' => 5000,
            ],
            [
                'name' => 'Bali Nusa Dua Convention Center',
                'address' => 'Kawasan BTDC Lot Nusa Dua, Benoa, Kec. Kuta Selatan',
                'city' => 'Bali',
                'capacity' => 3000,
            ],
            [
                'name' => 'Surabaya Cyber Arena',
                'address' => 'Jl. Pemuda No.85-87, Embong Kaliasin, Kec. Genteng',
                'city' => 'Surabaya',
                'capacity' => 1500,
            ],
            [
                'name' => 'Bandung Esports Hub',
                'address' => 'Jl. Buah Batu No.128, Pulaupanjang, Kec. Coblong',
                'city' => 'Bandung',
                'capacity' => 1200,
            ],
            [
                'name' => 'Medan Digital Arena',
                'address' => 'Jl. Putri Hijau No.10, Kesawan, Kec. Medan Barat',
                'city' => 'Medan',
                'capacity' => 800,
            ],
        ];

        $zones = ['Alpha', 'Bravo', 'Charlie'];
        $zoneDescriptions = [
            'Alpha' => 'Premium seating area with the best view of the main stage',
            'Bravo' => 'Standard seating with clear sightlines and full audio',
            'Charlie' => 'General admission area with large screen displays',
        ];

        foreach ($venues as $venue) {
            $venue = Venue::create([
                ...$venue,
                'map_embed_url' => 'https://maps.google.com/embed?q=' . urlencode($venue['address']),
            ]);

            foreach ($zones as $zone) {
                VenueZone::create([
                    'venue_id' => $venue->id,
                    'name' => 'Zone ' . $zone,
                    'description' => $zoneDescriptions[$zone],
                ]);
            }
        }
    }
}
