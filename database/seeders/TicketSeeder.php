<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\TicketBatch;
use App\Models\VenueZone;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $featuredMatch = GameMatch::where('is_featured', true)->first();

        if ($featuredMatch) {
            $zones = VenueZone::where('venue_id', $featuredMatch->venue_id)->get();

            $batches = [
                ['tier_name' => 'VIP', 'price' => 250000, 'seats_total' => 50, 'seats_remaining' => 12, 'status_badge' => 'Limited Seats'],
                ['tier_name' => 'Grandstand', 'price' => 150000, 'seats_total' => 200, 'seats_remaining' => 85, 'status_badge' => 'Selling Fast'],
                ['tier_name' => 'Regular', 'price' => 75000, 'seats_total' => 500, 'seats_remaining' => 310, 'status_badge' => null],
            ];

            foreach ($batches as $i => $batch) {
                TicketBatch::create([
                    'match_id' => $featuredMatch->id,
                    'venue_zone_id' => $zones[$i]->id ?? $zones->first()->id,
                    ...$batch,
                ]);
            }
        }

        $liveMatch = GameMatch::where('status', 'live')->first();

        if ($liveMatch) {
            $zones = VenueZone::where('venue_id', $liveMatch->venue_id)->get();

            TicketBatch::create([
                'match_id' => $liveMatch->id,
                'venue_zone_id' => $zones->first()->id,
                'tier_name' => 'Regular',
                'price' => 50000,
                'seats_total' => 300,
                'seats_remaining' => 0,
                'status_badge' => 'Sold Out',
            ]);
        }

        $upcomingMatches = GameMatch::where('status', 'upcoming')->where('is_featured', false)->take(2)->get();

        foreach ($upcomingMatches as $match) {
            $zones = VenueZone::where('venue_id', $match->venue_id)->get();

            TicketBatch::create([
                'match_id' => $match->id,
                'venue_zone_id' => $zones->first()->id,
                'tier_name' => 'Regular',
                'price' => 50000,
                'seats_total' => 400,
                'seats_remaining' => 400,
                'status_badge' => null,
            ]);
        }
    }
}
