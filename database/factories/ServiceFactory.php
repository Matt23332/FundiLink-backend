<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'location' => $this->faker->city(),
            'contact_info' => $this->faker->phoneNumber(),
            'category_id' => \App\Models\Categories::factory(),
            'user_id' => \App\Models\User::factory(),
            'image_path' => null,
        ];
    }
}
