<?php

namespace Tests\Feature\Admin;

use App\Models\AnimatedSeries;
use App\Models\Country;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnimatedSeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_animated_series_card_without_seasons(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.animated-series.store'), [
                'title' => 'Симпсоны',
                'year' => 1989,
            ])
            ->assertRedirect();

        $animatedSeries = AnimatedSeries::query()->first();

        $this->assertNotNull($animatedSeries);
        $this->assertSame('Симпсоны', $animatedSeries->getTranslation('title', 'ru'));
        $this->assertSame(0, $animatedSeries->seasons()->count());

        $this->actingAs($user)
            ->get(route('admin.animated-series.edit', $animatedSeries))
            ->assertOk()
            ->assertSee('Симпсоны')
            ->assertSee('Сезонов пока нет');
    }

    public function test_admin_adds_seasons_and_episodes(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('animated-series/Simpsons.S01E01.mp4', 'one');
        Storage::disk('media')->put('animated-series/Simpsons.S01E02.mp4', 'two');

        $user = User::factory()->create();
        $animatedSeries = AnimatedSeries::factory()->create([
            'title' => ['ru' => 'Симпсоны'],
        ]);

        $this->actingAs($user)
            ->post(route('admin.animated-series.seasons.store', $animatedSeries), [
                'number' => 1,
                'title' => 'Первый сезон',
            ])
            ->assertRedirect(route('admin.animated-series.edit', $animatedSeries));

        $season = $animatedSeries->seasons()->first();

        $this->assertNotNull($season);
        $this->assertSame(1, $season->number);

        $this->actingAs($user)
            ->post(route('admin.animated-series.episodes.store', [$animatedSeries, $season]), [
                'number' => 1,
                'title' => 'Пилот',
                'description' => 'Начало истории.',
                'filename' => 'Simpsons.S01E01.mp4',
            ])
            ->assertRedirect(route('admin.animated-series.edit', $animatedSeries));

        $this->actingAs($user)
            ->post(route('admin.animated-series.episodes.store', [$animatedSeries, $season]), [
                'number' => 2,
                'title' => 'Барт гений',
                'filename' => 'Simpsons.S01E02.mp4',
            ])
            ->assertRedirect(route('admin.animated-series.edit', $animatedSeries));

        $this->assertSame(2, $season->episodes()->count());
        $this->assertDatabaseHas('animated_episodes', [
            'animated_season_id' => $season->id,
            'filename' => 'Simpsons.S01E01.mp4',
            'title' => 'Пилот',
            'number' => 1,
        ]);
    }

    public function test_episode_file_must_exist_in_animated_series_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $animatedSeries = AnimatedSeries::factory()->create();
        $season = $animatedSeries->seasons()->create(['number' => 1]);

        $this->actingAs($user)
            ->post(route('admin.animated-series.episodes.store', [$animatedSeries, $season]), [
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
        $comedy = Genre::query()->create(['name' => 'Комедия']);
        $usa = Country::query()->create(['name' => 'США']);

        $this->actingAs($user)
            ->post(route('admin.animated-series.store'), [
                'title' => 'Симпсоны',
                'genre_ids' => [$comedy->id],
                'country_ids' => [$usa->id],
            ])
            ->assertRedirect();

        $animatedSeries = AnimatedSeries::query()->first();

        $this->assertNotNull($animatedSeries);
        $this->assertEqualsCanonicalizing([$comedy->id], $animatedSeries->genres()->pluck('genres.id')->all());
        $this->assertEqualsCanonicalizing([$usa->id], $animatedSeries->countries()->pluck('countries.id')->all());
    }

    public function test_episode_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('animated-series/Simpsons.S01E01.mp4', 'one');
        Storage::disk('media')->put('animated-series/Futurama.mkv', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.animated-series.episodes.search', ['q' => 'simp']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['Simpsons.S01E01.mp4'],
            ]);
    }

    public function test_admin_can_upload_a_poster(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.animated-series.store'), [
                'title' => 'Симпсоны',
                'poster' => UploadedFile::fake()->image('poster.jpg', 400, 600),
            ])
            ->assertRedirect();

        $animatedSeries = AnimatedSeries::query()->first();

        $this->assertNotNull($animatedSeries?->poster);
        Storage::disk('media')->assertExists($animatedSeries->poster);
    }

    public function test_admin_animated_series_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.animated-series'))
            ->assertOk()
            ->assertSee('Мультсериалы')
            ->assertSee('Пока пусто');
    }
}
