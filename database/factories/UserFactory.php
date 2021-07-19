<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     * @noinspection PhpUndefinedMethodInspection
     */
    public function definition(): array
    {
        return [
            'phone' => phone($this->faker->unique()
                ->numerify('53########'), 'TR')->formatE164(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'date_of_birth' => $this->faker
                ->dateTimeBetween('-80 years', '-6 years')
                ->format('Y-m-d'),
            'otp' => bcrypt('1234'),
            'otp_expiry' => now()->addSeconds(120),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): UserFactory
    {
        return $this->afterMaking(function (User $user) {
            $user->generateUniqueUsername();
        })->afterCreating(function (User $user) {
            //
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     * @noinspection PhpUnused
     * @noinspection PhpUnusedParameterInspection
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function dev(): UserFactory
    {
        return $this->state([
            'phone' => config('seeding.dev.phone'),
            'first_name' => config('seeding.dev.first_name'),
            'last_name' => config('seeding.dev.last_name'),
            'email' => config('seeding.dev.email'),
        ]);
    }

    public function dev_interact(): UserFactory
    {
        return $this->state([
            'first_name' => config('seeding.dev.first_name'),
            'last_name' => config('seeding.dev.last_name') . ' Interact',
            'email' => 'interact.' . config('seeding.dev.email'),
        ]);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function juvenile(): UserFactory
    {
        return $this->state([
            'date_of_birth' => $this->faker
                ->dateTimeBetween('-12 years', '-6 years')
                ->format('Y-m-d'),
            'parent_first_name' => $this->faker->firstName(),
            'parent_last_name' => $this->faker->lastName(),
            'parent_phone' => phone($this->faker->unique()
                ->numerify('53########'), 'TR')->formatE164(),
        ]);
    }
}
