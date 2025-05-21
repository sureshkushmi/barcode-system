<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'shipment_id' => Shipment::inRandomOrder()->first()?->id ?? Shipment::factory(),
            'name' => $this->faker->word(),
            'barcode' => $this->faker->unique()->ean13(),
            'required_quantity' => $this->faker->numberBetween(1, 20),
            'scanned_quantity' => $this->faker->numberBetween(0, 20),
            'completed' => $this->faker->boolean(),
        ];
    }
}
