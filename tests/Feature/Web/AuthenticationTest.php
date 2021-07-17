<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Tests\Feature\Web;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;


    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_otp_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'phone' => $user->phone,
            'phone_country' => phone($user->phone)->getCountry(),
        ]);

        $response->assertRedirect(route('otp'));
    }

    public function test_users_can_authenticate_using_the_otp_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/confirm-otp', [
            'phone' => $user->phone,
            'otp' => '1234',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/confirm-otp', [
            'phone' => $user->phone,
            'otp' => 'wrong-otp',
        ]);

        $this->assertGuest();
    }
}
