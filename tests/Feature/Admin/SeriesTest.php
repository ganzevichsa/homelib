<?php

namespace Tests\Feature\Admin;

use App\Models\Country;
use App\Models\Genre;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_series_card_without_seasons(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.series.store'), [
                'title' => 'Во все тяжкие',
                'year' => 2008,
            ])
            ->assertRedirect();

        $series = Series::query()->first();

        $this->assertNotNull($series);
        $this->assertSame('Во все тяжкие', $series->getTranslation('title', 'ru'));
        $this->assertSame(0, $series->seasons()->count());

        $this->actingAs($user)
            ->get(route('admin.series.edit', $series))
            ->assertOk()
            ->assertSee('Во все тяжкие')
            ->assertSee('Сезонов пока нет');
    }

    public function test_admin_adds_seasons_and_episodes(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('series/Breaking.Bad.S01E01.mp4', 'one');
        Storage::disk('media')->put('series/Breaking.Bad.S01E02.mp4', 'two');

        $user = User::factory()->create();
        $series = Series::factory()->create([
            'title' => ['ru' => 'Во все тяжкие'],
        ]);

        $this->actingAs($user)
            ->post(route('admin.series.seasons.store', $series), [
                'number' => 1,
                'title' => 'Первый сезон',
            ])
            ->assertRedirect(route('admin.series.edit', $series));

        $season = $series->seasons()->first();

        $this->assertNotNull($season);
        $this->assertSame(1, $season->number);

        $this->actingAs($user)
            ->post(route('admin.series.episodes.store', [$series, $season]), [
                'number' => 1,
                'title' => 'Пилот',
                'description' => 'Начало истории.',
                'filename' => 'Breaking.Bad.S01E01.mp4',
            ])
            ->assertRedirect(route('admin.series.edit', $series));

        $this->actingAs($user)
            ->post(route('admin.series.episodes.store', [$series, $season]), [
                'number' => 2,
                'title' => 'Кошка в мешке',
                'filename' => 'Breaking.Bad.S01E02.mp4',
            ])
            ->assertRedirect(route('admin.series.edit', $series));

        $this->assertSame(2, $season->episodes()->count());
        $this->assertDatabaseHas('episodes', [
            'season_id' => $season->id,
            'filename' => 'Breaking.Bad.S01E01.mp4',
            'title' => 'Пилот',
            'description' => 'Начало истории.',
            'number' => 1,
        ]);
    }

    public function test_episode_file_must_exist_in_series_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $series = Series::factory()->create();
        $season = $series->seasons()->create(['number' => 1]);

        $this->actingAs($user)
            ->post(route('admin.series.episodes.store', [$series, $season]), [
                'number' => 1,
                'title' => 'Нет файла',
                'filename' => 'Missing.mkv',
            ])
            ->assertSessionHasErrors('filename');

        $this->assertSame(0, $season->episodes()->count());
    }

    public function test_admin_can_attach_genres_and_countries(): void
    {
        $user = User::factory()->create();
        $drama = Genre::query()->create(['name' => 'Драма']);
        $crime = Genre::query()->create(['name' => 'Криминал']);
        $usa = Country::query()->create(['name' => 'США']);

        $this->actingAs($user)
            ->post(route('admin.series.store'), [
                'title' => 'Во все тяжкие',
                'genre_ids' => [$drama->id, $crime->id],
                'country_ids' => [$usa->id],
            ])
            ->assertRedirect();

        $series = Series::query()->first();

        $this->assertNotNull($series);
        $this->assertEqualsCanonicalizing(
            [$drama->id, $crime->id],
            $series->genres()->pluck('genres.id')->all(),
        );
        $this->assertEqualsCanonicalizing(
            [$usa->id],
            $series->countries()->pluck('countries.id')->all(),
        );
    }

    public function test_add_episode_page_does_not_list_directory_files(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('series/Hidden.File.mkv', 'one');

        $user = User::factory()->create();
        $series = Series::factory()->create();
        $season = $series->seasons()->create(['number' => 1]);

        $this->actingAs($user)
            ->get(route('admin.series.episodes.create', [$series, $season]))
            ->assertOk()
            ->assertDontSee('Hidden.File.mkv')
            ->assertSee('начни вводить имя');
    }

    public function test_episode_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('series/Breaking.Bad.S01E01.mp4', 'one');
        Storage::disk('media')->put('series/Better.Call.Saul.mkv', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.series.episodes.search', ['q' => 'break']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['Breaking.Bad.S01E01.mp4'],
            ]);
    }

    public function test_admin_can_upload_a_poster(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.series.store'), [
                'title' => 'Во все тяжкие',
                'poster' => UploadedFile::fake()->image('poster.jpg', 400, 600),
            ])
            ->assertRedirect();

        $series = Series::query()->first();

        $this->assertNotNull($series?->poster);
        Storage::disk('media')->assertExists($series->poster);
    }

    public function test_admin_series_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.series'))
            ->assertOk()
            ->assertSee('Сериалы')
            ->assertSee('Пока пусто');
    }
}
