<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpParamsInspection */

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\PostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanctum_authorization()
    {
        $response = $this->getJson(action([PostController::class, 'store']));
        $response->assertUnauthorized();
    }

    public function test_single_post_can_be_retrieved()
    {
        $user = User::factory()->create();
        $post = Post::factory(['user_id' => $user->id])->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(action([PostController::class, 'show'], $post->id));
        $response->assertOk();
    }

    public function test_users_posts_can_be_retrieved()
    {
        Sanctum::actingAs(User::factory()->hasPosts(5)->create());
        $response = $this->getJson(action([PostController::class, 'index']));
        $response->assertJsonCount(5);
    }

    public function test_timeline_can_be_retrieved_and_contains_follows_posts()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $user1->follow($user2);
        $post1 = Post::factory(['user_id' => $user1->id])->create();
        $post2 = Post::factory(['user_id' => $user2->id])->create();
        $post3 = Post::factory(['user_id' => $user3->id])->create();

        Sanctum::actingAs($user1);

        $response = $this->getJson(action([PostController::class, 'timeline']));

        $response->assertJsonCount(2);
        $response->assertJsonFragment(['id' => $post1->id]);
        $response->assertJsonFragment(['id' => $post2->id]);
        $response->assertJsonMissing(['id' => $post3->id]);
    }
}
