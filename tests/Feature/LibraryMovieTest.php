<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

        $movie->genres()->attach(Genre::query()->create(['name' => 'Фантастика']));
        $movie->countries()->attach(Country::query()->create(['name' => 'США']));

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
            ->assertSee('Первая часть трилогии.')
            ->assertSee('Фантастика')
            ->assertSee('США')
            ->assertSee('Чтобы смотреть')
            ->assertDontSee('<video', false);
    }

    public function test_authenticated_user_sees_the_player(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create([
            'title' => [Locale::Ru->value => 'Матрица'],
        ]);

        $movie->files()->create([
            'title' => 'Матрица 1999',
            'filename' => 'Matrix.1999.mp4',
            'path' => 'movies/Matrix.1999.mp4',
            'extension' => 'mp4',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('library.movie', $movie))
            ->assertOk()
            ->assertSee('<video', false)
            ->assertSee('stream', false);
    }

    public function test_guest_cannot_stream_a_movie_file(): void
    {
        $movie = Movie::factory()->create();
        $file = $movie->files()->create([
            'title' => 'Часть 1',
            'filename' => 'Movie.mp4',
            'path' => 'movies/Movie.mp4',
            'extension' => 'mp4',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.movie.stream', [$movie, $file]))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_stream_a_movie_file(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Movie.mp4', 'video-bytes');

        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        $file = $movie->files()->create([
            'title' => 'Часть 1',
            'filename' => 'Movie.mp4',
            'path' => 'movies/Movie.mp4',
            'extension' => 'mp4',
            'mime_type' => 'video/mp4',
            'size' => 11,
            'sort_order' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('library.movie.stream', [$movie, $file]))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/mp4');
    }

    public function test_poster_is_served_when_present(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('posters/1.jpg', 'image-bytes');

        $movie = Movie::factory()->create([
            'poster' => 'posters/1.jpg',
        ]);

        $this->get(route('library.movie.poster', $movie))
            ->assertOk();
    }

    public function test_cannot_stream_file_from_another_movie(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Movie.mp4', 'video-bytes');

        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        $other = Movie::factory()->create();
        $file = $other->files()->create([
            'title' => 'Чужой файл',
            'filename' => 'Movie.mp4',
            'path' => 'movies/Movie.mp4',
            'extension' => 'mp4',
            'size' => 11,
            'sort_order' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('library.movie.stream', [$movie, $file]))
            ->assertNotFound();
    }
}
