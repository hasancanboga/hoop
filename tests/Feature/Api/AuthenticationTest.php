<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\Auth\ConfirmOtpController;
use App\Http\Controllers\Api\Auth\LoginController;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register_request()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->postJson(action([LoginController::class, 'store']), [
            'phone' => $this->faker->unique()->numerify('53########'),
            'phone_country' => 'TR',
        ]);

        $response->assertStatus(200);
    }

    public function test_login_request()
    {
        $user = User::factory()->create();

        $response = $this->postJson(action([LoginController::class, 'store']), [
            'phone' => $user->phone,
            'phone_country' => phone($user->phone)->getCountry(),
        ]);

        $response->assertStatus(200);
    }

    public function test_confirm_otp_request()
    {
        $user = User::factory()->create();

        $response = $this->postJson(action([ConfirmOtpController::class, 'store']), [
            'phone' => $user->phone,
            'otp' => '1234',
        ]);

        $response->assertStatus(201);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson(action([ConfirmOtpController::class, 'store']), [
            'phone' => $user->phone,
            'otp' => 'wrong-otp',
        ]);

        $response->assertStatus(422);
    }
}
