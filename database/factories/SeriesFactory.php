<?php

namespace Database\Factories;

use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Series>
 */
class SeriesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'original_title' => fake()->optional()->words(3, true),
            'description' => $this->translations(['ru'], fake()->sentence()),
            'year' => fake()->optional()->numberBetween(1950, 2026),
        ];
    }
}
