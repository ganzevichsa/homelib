<?php

namespace Database\Factories;

use App\Enums\MediaType;
use App\Models\MediaItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaItem>
 */
class MediaItemFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(MediaType::cases());
        $filename = fake()->unique()->slug().'.mkv';

        return [
            'type' => $type,
            'title' => $this->translations(['ru', 'en'], fake()->words(3, true)),
            'original_title' => fake()->optional()->words(3, true),
            'description' => $this->translations(['ru', 'en'], fake()->sentence()),
            'path' => $type->directory().'/'.$filename,
            'filename' => $filename,
            'mime_type' => 'video/x-matroska',
            'extension' => 'mkv',
            'size' => fake()->numberBetween(1_000_000, 10_000_000_000),
            'hash' => hash('sha256', $filename),
            'year' => fake()->optional()->numberBetween(1950, 2026),
            'metadata' => [],
        ];
    }
}
