<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterParentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_sanctum_authorization()
    {
        $response = $this->postJson('/api/register-parent');
        $response->assertUnauthorized();
    }

    public function test_new_users_can_register_parent()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "birth_year" => now()->year - 6,
            ])
        );

        $response = $this->postJson('/api/register-parent', [
            'parent_first_name' => $this->faker->firstName(),
            'parent_last_name' => $this->faker->lastName(),
            'parent_phone' => $this->faker->unique()->numerify('53########'),
            'parent_phone_country' => "TR",
        ]);

        $response->assertStatus(200);
    }

    public function test_complete_registration_validation_rules()
    {
        Sanctum::actingAs(
            User::factory()->create([
                "birth_year" => now()->year - 6,
            ])
        );

        $response = $this->postJson('/api/register-parent');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parent_first_name', 'parent_last_name', 'parent_phone']);
    }
}
