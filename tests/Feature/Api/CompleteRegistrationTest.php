<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompleteRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson('/api/complete-registration');
        $response->assertUnauthorized();
    }

    public function test_new_users_can_complete_registration()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "first_name" => null,
                "last_name" => null,
                "email" => null,
                "gender" => null,
                "birth_year" => null,
            ])
        );

        $response = $this->postJson('/api/complete-registration', [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_year' => $this->faker->numberBetween(1921, 2020),
            'locality' => "",
        ]);

        $response->assertStatus(200);
    }

    public function test_complete_registration_validation_rules()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "first_name" => null,
                "last_name" => null,
                "email" => null,
                "gender" => null,
                "birth_year" => null,
            ])
        );

        $response = $this->postJson('/api/complete-registration');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name', 'gender', 'birth_year']);
    }
}
