<?php

namespace Database\Factories;

use App\Models\FileEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FileEntry>
 */
class FileEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->translations(['ru'], fake()->words(3, true)),
            'description' => $this->translations(['ru'], fake()->sentence()),
        ];
    }
}
