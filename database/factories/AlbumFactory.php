<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'artist' => fake()->name(),
            'original_title' => fake()->optional()->words(3, true),
            'description' => $this->translations(['ru'], fake()->sentence()),
            'year' => fake()->optional()->numberBetween(1950, 2026),
        ];
    }
}
