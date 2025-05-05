<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlacklistedWorker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optionally, you can seed a specific user
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create 10 random users using the UserFactory
        //User::factory(10)->create(); // Creates 10 random users
        BlacklistedWorker::factory(10)->create();
    }
}

