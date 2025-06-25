<?php

namespace Database\Factories;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitFactory extends Factory
{
    protected $model = \App\Models\Kit::class;

    public function definition()
    {
        return [
            'shipment_id' => Shipment::inRandomOrder()->first()->id, // get random shipment id from DB
            'barcode' => $this->faker->unique()->ean13(),
            'name' => 'Kit ' . $this->faker->unique()->word,
        ];
    }
}
