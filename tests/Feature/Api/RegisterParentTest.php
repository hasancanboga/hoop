<?php /** @noinspection PhpParamsInspection */

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\Auth\RegisterParentController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegisterParentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson(action([RegisterParentController::class, 'store']));
        $response->assertUnauthorized();
    }

    public function test_new_users_can_register_parent()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "date_of_birth" => today()->subYears(6),
            ])
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->postJson(action([RegisterParentController::class, 'store']), [
            'parent_first_name' => $this->faker->firstName(),
            'parent_last_name' => $this->faker->lastName(),
            'parent_phone' => $this->faker->unique()->numerify('53########'),
            'parent_phone_country' => "TR",
        ]);

        $response->assertStatus(200);
    }

    public function test_register_parent_validation_rules()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "date_of_birth" => today()->subYears(6),
            ])
        );

        $response = $this->postJson(action([RegisterParentController::class, 'store']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'parent_first_name',
            'parent_last_name',
            'parent_phone'
        ]);
    }
}
