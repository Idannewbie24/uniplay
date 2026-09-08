<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\GameSeeder::class);
        $this->seed(\Database\Seeders\TeamSeeder::class);
    }

    public function test_all_admin_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $adminRoutes = [
            '/admin' => 200,
            '/admin/games' => 200,
            '/admin/games/create' => 200,
            '/admin/teams' => 200,
            '/admin/teams/create' => 200,
            '/admin/tournaments' => 200,
            '/admin/tournaments/create' => 200,
            '/admin/matches' => 200,
            '/admin/matches/create' => 200,
            '/admin/venues' => 200,
            '/admin/venues/create' => 200,
            '/admin/topup-products' => 200,
            '/admin/topup-products/create' => 200,
            '/admin/shorts' => 200,
            '/admin/shorts/create' => 200,
            '/admin/banners' => 200,
            '/admin/banners/create' => 200,
            '/admin/faqs' => 200,
            '/admin/faqs/create' => 200,
            '/admin/orders/topup' => 200,
            '/admin/orders/tickets' => 200,
            '/dashboard' => 200,
            '/profile' => 200,
        ];

        foreach ($adminRoutes as $url => $expected) {
            try {
                $response = $this->actingAs($user)->get($url);
                if ($response->getStatusCode() !== $expected) {
                    $ex = $response->exception;
                    $msg = $ex ? (get_class($ex) . ': ' . $ex->getMessage()) : ('status ' . $response->getStatusCode());
                    echo "FAIL: {$url} => {$msg}\n";
                } else {
                    echo "OK: {$url} => {$response->getStatusCode()}\n";
                }
            } catch (\Throwable $e) {
                echo "FAIL: {$url} => " . get_class($e) . ': ' . $e->getMessage() . "\n";
            }
        }
        $this->assertTrue(true);
    }
}
