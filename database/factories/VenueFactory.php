<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Arena',
            'address' => fake()->address(),
            'city' => fake()->city(),
            'map_embed_url' => 'https://maps.google.com/embed?q=' . urlencode(fake()->address()),
            'capacity' => fake()->numberBetween(200, 5000),
        ];
    }
}
