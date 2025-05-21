<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Item;    // <--- Add this import
use Illuminate\Database\Eloquent\Factories\Factory;

class ScanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),        // creates a new user on demand
            'shipment_id' => Shipment::factory(),// creates a new shipment on demand
            'item_id' => Item::inRandomOrder()->first()?->id ?? Item::factory(), // fix here
            'quantity_scanned' => $this->faker->numberBetween(1, 10),
            'scanned_at' => now(),
            'status' => $this->faker->randomElement(['pending', 'scanned', 'confirmed']),
        ];
    }
}
