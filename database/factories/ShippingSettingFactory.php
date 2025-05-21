<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'api_key' => $this->faker->uuid(),
            'api_secret' => $this->faker->sha256,
            'store_api_key' => $this->faker->uuid(),
            'api_url' => $this->faker->url(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
