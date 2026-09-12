<?php

namespace Database\Factories;

use App\Models\Credential;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Credential>
 */
class CredentialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'login' => fake()->userName(),
            'password' => 'secret',
            'url' => fake()->optional()->url(),
            'ip' => fake()->optional()->ipv4(),
            'protocol' => fake()->optional()->randomElement(['ssh', 'http', 'rdp']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
