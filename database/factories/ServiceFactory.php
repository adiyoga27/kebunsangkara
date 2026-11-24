<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'summary' => fake()->sentence(12),
            'description' => fake()->paragraph(),
            'sort_order' => 0,
            'is_published' => true,
        ];
    }
}
