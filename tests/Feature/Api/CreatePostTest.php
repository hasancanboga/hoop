<?php /** @noinspection PhpParamsInspection */

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\PostController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson(action([PostController::class, 'store']));
        $response->assertUnauthorized();
    }

    public function test_post_validation_rules()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(action([PostController::class, 'store']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'body',
        ]);
    }

    public function test_post_can_be_created()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(action([PostController::class, 'store']), [
            'body' => 'Lorem ipsum dolar sit amet'
        ]);

        $response->assertStatus(200);
    }
}
