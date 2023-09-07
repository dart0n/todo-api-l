<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()
            ->create([
                'email' => 'user1@mail.com',
                'password' => 'password',
            ]);
        $user2 = User::factory()
            ->create([
                'email' => 'user2@mail.com',
                'password' => 'password',
            ]);

        Project::factory(3)->create(['user_id' => $user1])->each(function ($project) use ($user1) {
            Task::factory()->count(5)->create([
                'user_id' => $user1,
                'project_id' => $project,
            ]);
        });

        Project::factory(1)->create(['user_id' => $user2])->each(function ($project) use ($user2) {
            Task::factory()->count(10)->create([
                'user_id' => $user2,
                'project_id' => $project,
            ]);
        });
    }
}
