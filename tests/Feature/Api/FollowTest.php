<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Api\FollowController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_follow_and_unfollow_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $response = $this->postJson(action(
            [FollowController::class, 'store'],
            ['user' => $user2]
        ));

        $response->assertOK();
        $this->assertTrue($user1->isFollowing($user2));

        $response = $this->postJson(action(
            [FollowController::class, 'destroy'],
            ['user' => $user2]
        ));

        $response->assertOK();
        $this->assertFalse($user1->isFollowing($user2));
    }
}
