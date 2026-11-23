<?php

namespace Database\Factories;

use App\Enums\FruitCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = 'Durian '.fake()->unique()->words(2, true),
            'slug' => Str::slug($name),
            'category' => FruitCategory::Durian,
            'variety' => fake()->randomElement(['Montong', 'Lokal Bali', 'Musang King', 'Bawor']),
            'origin' => fake()->randomElement(['Pupuan, Tabanan', 'Munduk, Buleleng', 'Jembrana']),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->numberBetween(5, 30) * 5000,
            'price_unit' => 'per kg',
            'is_available' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    public function category(FruitCategory $category): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => $category,
            'name' => $name = $category->label().' '.fake()->unique()->words(2, true),
            'slug' => Str::slug($name),
        ]);
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes): array => ['is_available' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => ['is_featured' => true]);
    }
}
