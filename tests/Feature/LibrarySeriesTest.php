<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibrarySeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_series_with_episodes(): void
    {
        $series = Series::factory()->create([
            'title' => [
                Locale::Ru->value => 'Во все тяжкие',
            ],
            'year' => 2008,
        ]);

        $series->genres()->attach(Genre::query()->create(['name' => 'Драма']));
        $series->countries()->attach(Country::query()->create(['name' => 'США']));

        $season = $series->seasons()->create([
            'number' => 1,
            'title' => 'Первый сезон',
        ]);

        $season->episodes()->create([
            'number' => 1,
            'title' => 'Пилот',
            'description' => 'Начало истории.',
            'filename' => 'Breaking.Bad.S01E01.mp4',
            'path' => 'series/Breaking.Bad.S01E01.mp4',
            'extension' => 'mp4',
            'size' => 10,
        ]);

        $this->get(route('library.show', MediaType::Series))
            ->assertOk()
            ->assertSee('Во все тяжкие')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.series', $series))
            ->assertOk()
            ->assertSee('Во все тяжкие')
            ->assertSee('Пилот')
            ->assertSee('Начало истории.')
            ->assertSee('Первый сезон')
            ->assertSee('Драма')
            ->assertSee('США')
            ->assertSee('<video', false)
            ->assertDontSee('Чтобы смотреть');
    }

    public function test_guest_can_stream_an_episode(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('series/Episode.mp4', 'video-bytes');

        $series = Series::factory()->create();
        $season = $series->seasons()->create(['number' => 1]);
        $episode = $season->episodes()->create([
            'number' => 1,
            'title' => 'Пилот',
            'filename' => 'Episode.mp4',
            'path' => 'series/Episode.mp4',
            'extension' => 'mp4',
            'mime_type' => 'video/mp4',
            'size' => 11,
        ]);

        $this->get(route('library.series.stream', [$series, $episode]))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/mp4');
    }

    public function test_poster_is_served_when_present(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('posters/series-1.jpg', 'image-bytes');

        $series = Series::factory()->create([
            'poster' => 'posters/series-1.jpg',
        ]);

        $this->get(route('library.series.poster', $series))
            ->assertOk();
    }

    public function test_cannot_stream_episode_from_another_series(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('series/Episode.mp4', 'video-bytes');

        $series = Series::factory()->create();
        $other = Series::factory()->create();
        $season = $other->seasons()->create(['number' => 1]);
        $episode = $season->episodes()->create([
            'number' => 1,
            'title' => 'Чужая серия',
            'filename' => 'Episode.mp4',
            'path' => 'series/Episode.mp4',
            'extension' => 'mp4',
            'size' => 11,
        ]);

        $this->get(route('library.series.stream', [$series, $episode]))
            ->assertNotFound();
    }

    public function test_series_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Series))
            ->assertOk()
            ->assertSee('Сериалы')
            ->assertSee('Пока пусто');
    }
}
