<?php

namespace Database\Factories;

use App\Models\BlacklistedWorker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlacklistedWorkerFactory extends Factory
{
    protected $model = BlacklistedWorker::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'reason' => $this->faker->sentence(8),
            'proof' => $this->faker->imageUrl(640, 480, 'people'),
            'reported_by' => User::factory(), // create user automatically or use static ID
            'approved' => $this->faker->boolean(70), // 70% chance it's approved
        ];
    }
}
