<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_adminidan_can_login_and_access_admin(): void
    {
        // user was created by RoleSeeder
        $this->post('/login', [
            'email' => 'adminidan@uniplay.com',
            'password' => '12345',
        ])->assertRedirect('/dashboard');

        $this->followingRedirects()->get('/admin')
            ->assertStatus(200);
    }

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::where('email', 'adminidan@uniplay.com')->first();
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }
}
