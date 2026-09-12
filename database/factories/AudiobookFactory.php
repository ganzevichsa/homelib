<?php

namespace Database\Factories;

use App\Models\Audiobook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Audiobook>
 */
class AudiobookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'author' => fake()->name(),
            'narrator' => fake()->optional()->name(),
            'description' => $this->translations(['ru'], fake()->sentence()),
            'year' => fake()->optional()->numberBetween(1950, 2026),
        ];
    }
}
