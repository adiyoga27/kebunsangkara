<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'location' => fake()->randomElement(['Denpasar', 'Kuta', 'Ubud', 'Canggu']),
            'content' => fake()->sentence(18),
            'rating' => 5,
            'is_published' => true,
        ];
    }
}
