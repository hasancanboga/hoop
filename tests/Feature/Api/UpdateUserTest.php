<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson(action([UserController::class, 'update']));
        $response->assertUnauthorized();
    }

    /** @noinspection PhpParamsInspection
     * @noinspection PhpUndefinedMethodInspection
     */
    public function test_users_can_update_themselves()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->postJson(action([UserController::class, 'update']), [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'username' => $this->faker->userName(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => $this->faker
                ->dateTimeBetween('-80 years', '-6 years')
                ->format('Y-m-d'),
            'locality' => "",
        ]);

        $response->assertStatus(200);
    }
}
