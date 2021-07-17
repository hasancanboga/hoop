<?php /** @noinspection PhpParamsInspection */

namespace Tests\Feature\Web;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompleteRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_guests_can_not_reach_complete_registration_screen()
    {
        $response = $this->get('/complete-registration');

        $response->assertRedirect(route('login'));
    }

    public function test_new_users_are_redirected_to_complete_registration_screen()
    {
        $user = User::factory()->create([
            "first_name" => null,
            "last_name" => null,
            "email" => null,
            "gender" => null,
            "birth_year" => null,
        ]);

        $response = $this->actingAs($user)->get(RouteServiceProvider::HOME);
        $response->assertRedirect(route('register.complete'));
    }

    public function test_registered_users_can_not_reach_complete_registration_screen()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register.complete'));
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_new_users_can_complete_registration()
    {
        $user = User::factory()->create([
            "first_name" => null,
            "last_name" => null,
            "email" => null,
            "gender" => null,
            "birth_year" => null,
        ]);

        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->actingAs($user)->post(route('register.complete'), [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_year' => $this->faker->numberBetween(1921, 2020),
            'locality' => "",
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_complete_registration_validation_rules()
    {
        $user = User::factory()->create([
            "first_name" => null,
            "last_name" => null,
            "email" => null,
            "gender" => null,
            "birth_year" => null,
        ]);

        $response = $this->actingAs($user)->post(route('register.complete'));

        $response->assertSessionHasErrors(['first_name', 'last_name', 'gender', 'birth_year']);
    }
}
