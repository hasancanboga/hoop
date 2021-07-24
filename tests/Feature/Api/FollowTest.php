<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\FollowController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanctum_authorization()
    {
        $user = User::factory()->create();

        $response = $this->postJson(action(
            [FollowController::class, 'store'],
            ['user' => $user]
        ));
        $response->assertUnauthorized();

        $response = $this->deleteJson(action(
            [FollowController::class, 'destroy'],
            ['user' => $user]
        ));
        $response->assertUnauthorized();
    }

    /** @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpParamsInspection
     */
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

        $response = $this->deleteJson(action(
            [FollowController::class, 'destroy'],
            ['user' => $user2]
        ));

        $response->assertOK();
        $this->assertFalse($user1->isFollowing($user2));
    }
}
