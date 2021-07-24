<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\PostLikesController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanctum_authorization()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->postJson(action(
            [PostLikesController::class, 'store'],
            ['post' => $post]
        ));
        $response->assertUnauthorized();

        $response = $this->deleteJson(action(
            [PostLikesController::class, 'destroy'],
            ['post' => $post]
        ));
        $response->assertUnauthorized();
    }

    /** @noinspection PhpParamsInspection
     * @noinspection PhpUndefinedMethodInspection
     */
    public function test_like_and_unlike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(action(
            [PostLikesController::class, 'store'],
            ['post' => $post]
        ));

        $response->assertOK();
        $this->assertTrue($post->isLikedBy($user));

        $response = $this->deleteJson(action(
            [PostLikesController::class, 'destroy'],
            ['post' => $post]
        ));

        $response->assertOK();
        $this->assertFalse($post->isLikedBy($user));
    }
}
