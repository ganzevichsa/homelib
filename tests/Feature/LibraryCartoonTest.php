<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Cartoon;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryCartoonTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_cartoon_with_files(): void
    {
        $cartoon = Cartoon::factory()->create([
            'title' => [
                Locale::Ru->value => 'Король Лев',
            ],
            'year' => 1994,
        ]);

        $cartoon->genres()->attach(Genre::query()->create(['name' => 'Семейный']));
        $cartoon->countries()->attach(Country::query()->create(['name' => 'США']));

        $cartoon->files()->create([
            'title' => 'Король Лев 1994',
            'description' => 'Первая часть.',
            'year' => 1994,
            'filename' => 'Lion.King.1994.mkv',
            'path' => 'cartoons/Lion.King.1994.mkv',
            'extension' => 'mkv',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::Cartoon))
            ->assertOk()
            ->assertSee('Король Лев')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.cartoon', $cartoon))
            ->assertOk()
            ->assertSee('Король Лев')
            ->assertSee('Король Лев 1994')
            ->assertSee('Первая часть.')
            ->assertSee('Семейный')
            ->assertSee('США')
            ->assertSee('<video', false);
    }

    public function test_guest_can_stream_a_cartoon_file(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('cartoons/Cartoon.mp4', 'video-bytes');

        $cartoon = Cartoon::factory()->create();
        $file = $cartoon->files()->create([
            'title' => 'Часть 1',
            'filename' => 'Cartoon.mp4',
            'path' => 'cartoons/Cartoon.mp4',
            'extension' => 'mp4',
            'mime_type' => 'video/mp4',
            'size' => 11,
            'sort_order' => 0,
        ]);

        $this->get(route('library.cartoon.stream', [$cartoon, $file]))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/mp4');
    }

    public function test_cannot_stream_file_from_another_cartoon(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('cartoons/Cartoon.mp4', 'video-bytes');

        $cartoon = Cartoon::factory()->create();
        $other = Cartoon::factory()->create();
        $file = $other->files()->create([
            'title' => 'Чужой файл',
            'filename' => 'Cartoon.mp4',
            'path' => 'cartoons/Cartoon.mp4',
            'extension' => 'mp4',
            'size' => 11,
            'sort_order' => 0,
        ]);

        $this->get(route('library.cartoon.stream', [$cartoon, $file]))
            ->assertNotFound();
    }

    public function test_cartoon_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Cartoon))
            ->assertOk()
            ->assertSee('Мультфильмы')
            ->assertSee('Пока пусто');
    }
}
