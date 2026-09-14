<?php

namespace Tests\Feature;

use App\Models\TicketOrder;
use App\Models\TopupOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->actingAs(User::where('email', 'adminidan@uniplay.com')->first());
    }

    public function test_revenue_counts_only_delivered_topup_orders(): void
    {
        $user = User::factory()->create();

        $topup = TopupOrder::create([
            'user_id'                  => $user->id,
            'topup_product_id'         => \App\Models\TopupProduct::factory()->create()->id,
            'topup_denomination_id'    => \App\Models\TopupDenomination::factory()->create()->id,
            'game_user_id'             => '12345678',
            'subtotal'                 => 100000,
            'total'                    => 100000,
            'status'                   => 'delivered',
        ]);

        TopupOrder::create([
            'user_id'                  => $user->id,
            'topup_product_id'         => \App\Models\TopupProduct::factory()->create()->id,
            'topup_denomination_id'    => \App\Models\TopupDenomination::factory()->create()->id,
            'game_user_id'             => '99999999',
            'subtotal'                 => 50000,
            'total'                    => 50000,
        ]);

        $this->assertSame(100000, (int) TopupOrder::where('status', 'delivered')->sum('total'));
    }

    public function test_topup_status_update_accepts_delivered_and_rejects_completed(): void
    {
        $order = TopupOrder::create([
            'user_id'                  => User::factory()->create()->id,
            'topup_product_id'         => \App\Models\TopupProduct::factory()->create()->id,
            'topup_denomination_id'    => \App\Models\TopupDenomination::factory()->create()->id,
            'game_user_id'             => '12345678',
            'subtotal'                 => 100000,
            'total'                    => 100000,
        ]);

        $this->patch("/admin/orders/topup/{$order->id}/status", ['status' => 'delivered'])
            ->assertRedirect();

        $this->assertSame('delivered', $order->fresh()->status);

        $this->patch("/admin/orders/topup/{$order->id}/status", ['status' => 'completed'])
            ->assertSessionHasErrors('status');

        $this->assertSame('delivered', $order->fresh()->status);
    }

    public function test_ticket_status_update_rejects_refunded(): void
    {
        $batch = $this->makeBatch();

        $order = TicketOrder::create([
            'user_id'          => User::factory()->create()->id,
            'ticket_batch_id'  => $batch->id,
            'buyer_name'       => 'Test Buyer',
            'buyer_phone'      => '0812345678',
            'quantity'         => 1,
            'total_price'      => 50000,
            'qr_code_token'    => 'ABC123',
        ]);

        $this->patch("/admin/orders/tickets/{$order->id}/status", ['status' => 'checked_in'])
            ->assertRedirect();

        $this->assertSame('checked_in', $order->fresh()->status);

        $this->patch("/admin/orders/tickets/{$order->id}/status", ['status' => 'refunded'])
            ->assertSessionHasErrors('status');

        $this->assertSame('checked_in', $order->fresh()->status);
    }

    protected function makeBatch()
    {
        return \App\Models\TicketBatch::create([
            'match_id'        => \App\Models\GameMatch::factory()->create()->id,
            'venue_zone_id'   => \App\Models\VenueZone::create([
                'venue_id'    => \App\Models\Venue::factory()->create()->id,
                'name'        => 'vip',
            ])->id,
            'price'           => 50000,
            'seats_total'     => 100,
            'seats_remaining' => 80,
        ]);
    }
}