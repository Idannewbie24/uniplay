<?php

namespace Tests\Feature;

use App\Models\AppNotification;
use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->actingAs(User::where('email', 'adminidan@uniplay.com')->first());
    }

    public function test_creating_upcoming_match_sends_schedule_notification(): void
    {
        $user = User::factory()->create();

        GameMatch::factory()->upcoming()->create([
            'is_featured' => true,
        ]);

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'type'    => 'schedule',
        ]);
    }

    public function test_updating_match_to_completed_sends_result_notification(): void
    {
        $user = User::factory()->create();
        $match = GameMatch::factory()->upcoming()->create();

        $match->update(['status' => 'finished', 'score_a' => 2, 'score_b' => 1]);

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'type'    => 'result',
            'message' => $match->teamA->tag.' vs '.$match->teamB->tag.' • 2 - 1',
        ]);
    }

    public function test_index_marks_and_deletes_notifications(): void
    {
        $admin = User::where('email', 'adminidan@uniplay.com')->first();

        GameMatch::factory()->upcoming()->create();

        $notification = AppNotification::where('user_id', $admin->id)->where('type', 'schedule')->firstOrFail();

        $this->get('/notifications')->assertOk()->assertJsonCount(1, 'notifications');
        $this->postJson("/notifications/{$notification->id}/read")->assertOk();
        $this->assertNotNull($notification->fresh()->read_at);

        $this->deleteJson("/notifications/{$notification->id}")->assertOk();
        $this->assertDatabaseMissing('user_notifications', ['id' => $notification->id]);
    }
}
