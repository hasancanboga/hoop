<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\Auth\CompleteRegistrationController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompleteRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson(action([CompleteRegistrationController::class, 'store']));
        $response->assertUnauthorized();
    }

    /**
     * @noinspection PhpParamsInspection
     * @noinspection PhpUndefinedMethodInspection
     */
    public function test_new_users_can_complete_registration()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "first_name" => null,
                "last_name" => null,
                "email" => null,
                "gender" => null,
                "date_of_birth" => null,
            ])
        );

        $response = $this->postJson(action([CompleteRegistrationController::class, 'store']), [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => $this->faker
                ->dateTimeBetween('-80 years', '-6 years')
                ->format('Y-m-d'),
            'locality' => "",
        ]);

        $response->assertStatus(200);
    }

    /** @noinspection PhpParamsInspection */
    public function test_complete_registration_validation_rules()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "first_name" => null,
                "last_name" => null,
                "email" => null,
                "gender" => null,
                "date_of_birth" => null,
            ])
        );

        $response = $this->postJson(action([CompleteRegistrationController::class, 'store']));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'gender',
            'date_of_birth'
        ]);
    }
}
