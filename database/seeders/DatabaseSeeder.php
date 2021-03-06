<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function run()
    {
        $dev = User::factory()->dev()->create();
        $dev2 = User::factory()->dev_interact()->create();
        Post::factory(['user_id' => $dev->id])->count(10)->create();
        Post::factory(['user_id' => $dev2->id])->count(10)->create();

        User::factory(10)->hasPosts(5)->create();
        User::factory()->juvenile()->hasPosts(5)->create();
        User::factory()->juvenile()->hasPosts(5)->create();
        User::factory()->juvenile()->hasPosts(5)->create();
    }
}
