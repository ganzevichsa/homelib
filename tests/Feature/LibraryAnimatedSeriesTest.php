<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\AnimatedSeries;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryAnimatedSeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_an_animated_series_with_episodes(): void
    {
        $animatedSeries = AnimatedSeries::factory()->create([
            'title' => [
                Locale::Ru->value => 'Симпсоны',
            ],
            'year' => 1989,
        ]);

        $animatedSeries->genres()->attach(Genre::query()->create(['name' => 'Комедия']));
        $animatedSeries->countries()->attach(Country::query()->create(['name' => 'США']));

        $season = $animatedSeries->seasons()->create([
            'number' => 1,
            'title' => 'Первый сезон',
        ]);

        $season->episodes()->create([
            'number' => 1,
            'title' => 'Пилот',
            'description' => 'Начало истории.',
            'filename' => 'Simpsons.S01E01.mp4',
            'path' => 'animated-series/Simpsons.S01E01.mp4',
            'extension' => 'mp4',
            'size' => 10,
        ]);

        $this->get(route('library.show', MediaType::AnimatedSeries))
            ->assertOk()
            ->assertSee('Симпсоны')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.animated-series', $animatedSeries))
            ->assertOk()
            ->assertSee('Симпсоны')
            ->assertSee('Пилот')
            ->assertSee('Начало истории.')
            ->assertSee('Первый сезон')
            ->assertSee('Комедия')
            ->assertSee('США')
            ->assertSee('<video', false);
    }

    public function test_guest_can_stream_an_episode(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('animated-series/Episode.mp4', 'video-bytes');

        $animatedSeries = AnimatedSeries::factory()->create();
        $season = $animatedSeries->seasons()->create(['number' => 1]);
        $episode = $season->episodes()->create([
            'number' => 1,
            'title' => 'Пилот',
            'filename' => 'Episode.mp4',
            'path' => 'animated-series/Episode.mp4',
            'extension' => 'mp4',
            'mime_type' => 'video/mp4',
            'size' => 11,
        ]);

        $this->get(route('library.animated-series.stream', [$animatedSeries, $episode]))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/mp4');
    }

    public function test_cannot_stream_episode_from_another_animated_series(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('animated-series/Episode.mp4', 'video-bytes');

        $animatedSeries = AnimatedSeries::factory()->create();
        $other = AnimatedSeries::factory()->create();
        $season = $other->seasons()->create(['number' => 1]);
        $episode = $season->episodes()->create([
            'number' => 1,
            'title' => 'Чужая серия',
            'filename' => 'Episode.mp4',
            'path' => 'animated-series/Episode.mp4',
            'extension' => 'mp4',
            'size' => 11,
        ]);

        $this->get(route('library.animated-series.stream', [$animatedSeries, $episode]))
            ->assertNotFound();
    }

    public function test_animated_series_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::AnimatedSeries))
            ->assertOk()
            ->assertSee('Мультсериалы')
            ->assertSee('Пока пусто');
    }
}
