<?php

namespace Database\Seeders;

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
        User::factory()->times(5)->create();
        $this->command->info('User is seeded');
        Topic::factory()->times(10)->create();
        $this->command->info('Topic is seeded');
    }
}
