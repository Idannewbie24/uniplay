<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\TicketBatch;
use App\Models\VenueZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketBatchFactory extends Factory
{
    protected $model = TicketBatch::class;

    public function definition(): array
    {
        $total = fake()->numberBetween(50, 500);

        return [
            'match_id' => GameMatch::factory(),
            'venue_zone_id' => VenueZone::factory(),
            'tier_name' => fake()->randomElement(['VIP', 'Grandstand', 'Regular', 'Economy']),
            'price' => fake()->randomFloat(2, 25000, 250000),
            'seats_total' => $total,
            'seats_remaining' => fake()->numberBetween(0, $total),
            'status_badge' => fake()->optional(0.6)->randomElement(['Selling Fast', 'Limited Seats', 'Almost Sold Out', 'Best Value']),
        ];
    }
}
