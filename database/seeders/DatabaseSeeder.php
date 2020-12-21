<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Topic;
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
        User::factory()->times(5)->create()->each(function($user){
            Topic::factory()->times(5)->create(['user_id' => $user->id])->each(function($topic) {
                Post::factory()->times(5)->create(['topic_id' => $topic->id]);
            });
        });
    }
}
