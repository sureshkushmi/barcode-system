<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ShippingSetting;
use App\Models\Shipment;
use App\Models\Item;
use App\Models\Scan;
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
        User::factory(10)->create(); // Creates 10 random users
       // BlacklistedWorker::factory(10)->create();
      //ShippingSetting::factory(10)->create();
      // Shipment::factory(10)->create();
      Item::factory(20)->create();      // Run this BEFORE Scan
    Scan::factory(30)->create();      // Relies on item_id, user_id, shipment_id

    }
}

