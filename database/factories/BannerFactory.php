<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(4, true),
            'image' => 'banners/' . fake()->uuid() . '.jpg',
            'link_url' => fake()->optional(0.8)->url(),
            'placement' => fake()->randomElement(['home_carousel', 'sidebar', 'topup_page', 'match_page']),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'is_active' => fake()->boolean(85),
        ];
    }
}
