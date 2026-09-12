<?php

namespace Database\Factories;

use App\Models\GalleryAlbum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryAlbum>
 */
class GalleryAlbumFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'description' => $this->translations(['ru'], fake()->sentence()),
            'year' => fake()->optional()->numberBetween(2000, 2026),
        ];
    }
}
