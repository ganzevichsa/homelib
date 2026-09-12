<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'description' => $this->translations(['ru'], fake()->sentence()),
            'year' => fake()->optional()->numberBetween(1990, 2026),
        ];
    }
}
