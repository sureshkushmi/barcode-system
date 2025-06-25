<?php

namespace Database\Factories;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'shippingeasy_order_id' => strtoupper(Str::random(10)),
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'status' => $this->faker->randomElement(['pending', 'shipped', 'cancelled']),
            'order_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
