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
     */
    public function definition()
    {
        return [
            'phone' => phone($this->faker->unique()
                ->numerify('53########'), 'TR')->formatE164(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'birth_year' => $this->faker->numberBetween(1921, 2020),
            'otp' => bcrypt('1234'),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
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
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function dev()
    {
        return $this->state([
            'phone' => config('seeding.dev.phone'),
            'first_name' => config('seeding.dev.first_name'),
            'last_name' => config('seeding.dev.last_name'),
            'email' => config('seeding.dev.email'),
        ]);
    }

    public function dev_interact()
    {
        return $this->state([
            'first_name' => config('seeding.dev.first_name'),
            'last_name' => config('seeding.dev.last_name') . ' Interact',
            'email' => 'interact.' . config('seeding.dev.email'),
        ]);
    }
}
