<?php

namespace Tests\Feature\Admin;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_movie_with_several_files(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('movies/Matrix.1999.mkv', 'one');
        Storage::disk('media')->put('movies/Matrix.Reloaded.mkv', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.movies.store'), [
                'title_ru' => 'Матрица',
                'title_en' => 'The Matrix',
                'year' => 1999,
                'files' => [
                    'Matrix.1999.mkv',
                    'Matrix.Reloaded.mkv',
                ],
                'file_titles' => [
                    'Matrix.1999.mkv' => 'Матрица 1999',
                    'Matrix.Reloaded.mkv' => 'Матрица Перезагрузка',
                ],
            ])
            ->assertRedirect(route('admin.movies'));

        $movie = Movie::query()->first();

        $this->assertNotNull($movie);
        $this->assertSame(2, $movie->files()->count());
        $this->assertDatabaseHas('movie_files', [
            'filename' => 'Matrix.1999.mkv',
            'title' => 'Матрица 1999',
        ]);
    }

    public function test_files_must_exist_in_movies_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.movies.store'), [
                'title_ru' => 'Нет файла',
                'files' => ['Missing.mkv'],
            ])
            ->assertSessionHasErrors('files.0');

        $this->assertSame(0, Movie::query()->count());
    }
}
