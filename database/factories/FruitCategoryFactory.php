<?php

namespace Database\Factories;

use App\Models\FruitCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FruitCategory>
 */
class FruitCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Buah '.fake()->unique()->numerify('####'),
            'slug' => fake()->unique()->numerify('buah-####'),
            'emoji' => '🍎',
            'tagline' => fake()->text(80),
            'intro' => fake()->text(200),
            'sort_order' => 10,
        ];
    }
}
