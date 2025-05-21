<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'tracking_number' => $this->faker->unique()->uuid,
            'status' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
        ];
    }
}
