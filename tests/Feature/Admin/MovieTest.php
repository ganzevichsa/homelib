<?php

namespace Tests\Feature\Admin;

use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_movie_card_without_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.movies.store'), [
                'title' => 'Матрица Трилогия',
                'year' => 1999,
            ])
            ->assertRedirect();

        $movie = Movie::query()->first();

        $this->assertNotNull($movie);
        $this->assertSame('Матрица Трилогия', $movie->getTranslation('title', 'ru'));
        $this->assertSame(0, $movie->files()->count());

        $this->actingAs($user)
            ->get(route('admin.movies.edit', $movie))
            ->assertOk()
            ->assertSee('Матрица Трилогия')
            ->assertSee('Файлов пока нет');
    }

    public function test_admin_adds_movie_files_one_by_one(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Matrix.1999.mkv', 'one');
        Storage::disk('media')->put('movies/Matrix.Reloaded.mkv', 'two');

        $user = User::factory()->create();
        $movie = Movie::factory()->create([
            'title' => ['ru' => 'Матрица Трилогия'],
        ]);

        $this->actingAs($user)
            ->post(route('admin.movies.files.store', $movie), [
                'title' => 'Матрица (1999)',
                'year' => 1999,
                'description' => 'Первая часть.',
                'filename' => 'Matrix.1999.mkv',
            ])
            ->assertRedirect(route('admin.movies.edit', $movie));

        $this->actingAs($user)
            ->post(route('admin.movies.files.store', $movie), [
                'title' => 'Матрица Перезагрузка',
                'year' => 2003,
                'description' => 'Вторая часть.',
                'filename' => 'Matrix.Reloaded.mkv',
            ])
            ->assertRedirect(route('admin.movies.edit', $movie));

        $this->assertSame(2, $movie->files()->count());
        $this->assertDatabaseHas('movie_files', [
            'movie_id' => $movie->id,
            'filename' => 'Matrix.1999.mkv',
            'title' => 'Матрица (1999)',
            'year' => 1999,
            'description' => 'Первая часть.',
        ]);
    }

    public function test_file_must_exist_in_movies_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.movies.files.store', $movie), [
                'title' => 'Нет файла',
                'filename' => 'Missing.mkv',
            ])
            ->assertSessionHasErrors('filename');

        $this->assertSame(0, $movie->files()->count());
    }

    public function test_admin_can_attach_genres_and_countries(): void
    {
        $user = User::factory()->create();
        $comedy = Genre::query()->create(['name' => 'Комедия']);
        $action = Genre::query()->create(['name' => 'Боевик']);
        $usa = Country::query()->create(['name' => 'США']);
        $uk = Country::query()->create(['name' => 'Великобритания']);

        $this->actingAs($user)
            ->post(route('admin.movies.store'), [
                'title' => 'Матрица Трилогия',
                'genre_ids' => [$comedy->id, $action->id],
                'country_ids' => [$usa->id, $uk->id],
            ])
            ->assertRedirect();

        $movie = Movie::query()->first();

        $this->assertNotNull($movie);
        $this->assertEqualsCanonicalizing(
            [$comedy->id, $action->id],
            $movie->genres()->pluck('genres.id')->all(),
        );
        $this->assertEqualsCanonicalizing(
            [$usa->id, $uk->id],
            $movie->countries()->pluck('countries.id')->all(),
        );
    }

    public function test_create_movie_page_does_not_scan_for_free_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.movies.create'))
            ->assertOk()
            ->assertSee('Матрица Трилогия')
            ->assertDontSee('Свободных файлов нет');
    }

    public function test_add_file_page_does_not_list_directory_files(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Hidden.File.mkv', 'one');

        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.movies.files.create', $movie))
            ->assertOk()
            ->assertDontSee('Hidden.File.mkv')
            ->assertSee('начни вводить имя');
    }

    public function test_file_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Matrix.1999.mkv', 'one');
        Storage::disk('media')->put('movies/Inception.2010.mkv', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.movies.files.search', ['q' => 'matr']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['Matrix.1999.mkv'],
            ]);
    }

    public function test_admin_can_upload_a_poster(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.movies.store'), [
                'title' => 'Матрица Трилогия',
                'poster' => UploadedFile::fake()->image('poster.jpg', 400, 600),
            ])
            ->assertRedirect();

        $movie = Movie::query()->first();

        $this->assertNotNull($movie?->poster);
        Storage::disk('media')->assertExists($movie->poster);
    }
}
