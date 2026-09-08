<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Game;
use App\Models\PaymentMethod;
use App\Models\Short;
use App\Models\Team;
use Database\Seeders\ContentSeeder;
use Database\Seeders\GameSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TeamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(GameSeeder::class);
        $this->seed(TeamSeeder::class);
        $this->seed(ContentSeeder::class);
    }

    public function test_all_seeded_images_have_files(): void
    {
        $missing = [];

        foreach (Game::all() as $m) {
            foreach (['icon', 'banner'] as $f) {
                $v = $m->{$f};
                if ($v && !file_exists(storage_path('app/public/' . $v))) $missing[] = "game:{$m->slug}:{$f}:{$v}";
            }
        }
        foreach (Team::all() as $m) {
            if ($m->logo && !file_exists(storage_path('app/public/' . $m->logo))) $missing[] = "team:{$m->tag}:{$m->logo}";
        }
        foreach (Banner::all() as $m) {
            if ($m->image && !file_exists(storage_path('app/public/' . $m->image))) $missing[] = "banner:{$m->title}:{$m->image}";
        }
        foreach (Short::all() as $m) {
            if ($m->thumbnail && !file_exists(storage_path('app/public/' . $m->thumbnail))) $missing[] = "short:{$m->title}:{$m->thumbnail}";
        }
        foreach (PaymentMethod::all() as $m) {
            if ($m->logo && !file_exists(storage_path('app/public/' . $m->logo))) $missing[] = "payment:{$m->name}:{$m->logo}";
        }

        foreach ($missing as $miss) {
            echo "MISSING: $miss\n";
        }
        $this->assertEmpty($missing, count($missing) . " image(s) missing files.");
    }
}
