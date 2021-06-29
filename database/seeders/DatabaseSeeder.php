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
     */
    public function run()
    {
        $dev = User::factory()->dev()->create();
        Post::factory(['user_id' => $dev->id])->count(10)->create();

        User::factory(10)->hasPosts(5)->create();
    }
}
