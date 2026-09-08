<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_render(): void
    {
        $routes = [
            '/' => 200,
            '/login' => 200,
            '/register' => 200,
            '/topup' => 200,
            '/tickets' => 200,
            '/schedule' => 200,
            '/standings' => 200,
            '/shorts' => 200,
            '/search?q=val' => 200,
        ];

        foreach ($routes as $url => $expected) {
            try {
                $response = $this->get($url);
                $this->assertEquals($expected, $response->getStatusCode(), "URL: {$url}");
                echo "OK: {$url} => {$response->getStatusCode()}\n";
            } catch (\Throwable $e) {
                echo "FAIL: {$url} => " . get_class($e) . ': ' . $e->getMessage() . "\n";
                $this->fail("URL {$url} errored: " . $e->getMessage());
            }
        }
    }
}
