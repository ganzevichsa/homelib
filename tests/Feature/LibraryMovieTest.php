<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibraryMovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_movie_with_files(): void
    {
        $movie = Movie::factory()->create([
            'title' => [
                Locale::Ru->value => 'Матрица',
            ],
            'year' => 1999,
        ]);

        $movie->files()->create([
            'title' => 'Матрица 1999',
            'description' => 'Первая часть трилогии.',
            'year' => 1999,
            'filename' => 'Matrix.1999.mkv',
            'path' => 'movies/Matrix.1999.mkv',
            'extension' => 'mkv',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::Movie))
            ->assertOk()
            ->assertSee('Матрица')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.movie', $movie))
            ->assertOk()
            ->assertSee('Матрица')
            ->assertSee('Матрица 1999')
            ->assertSee('Первая часть трилогии.');
    }
}
