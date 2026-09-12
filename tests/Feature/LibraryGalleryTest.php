<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\GalleryAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_an_album(): void
    {
        $album = GalleryAlbum::factory()->create([
            'title' => [Locale::Ru->value => 'Отпуск'],
            'year' => 2024,
        ]);

        $album->items()->create([
            'title' => 'Пляж',
            'filename' => 'Vacation.jpg',
            'path' => 'gallery/Vacation.jpg',
            'extension' => 'jpg',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::Gallery))
            ->assertOk()
            ->assertSee('Отпуск')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.gallery', $album))
            ->assertOk()
            ->assertSee('Отпуск')
            ->assertSee('Пляж');
    }

    public function test_guest_can_view_a_photo(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('gallery/Vacation.jpg', 'photo-bytes');

        $album = GalleryAlbum::factory()->create();
        $item = $album->items()->create([
            'title' => 'Пляж',
            'filename' => 'Vacation.jpg',
            'path' => 'gallery/Vacation.jpg',
            'extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'size' => 11,
            'sort_order' => 0,
        ]);

        $this->get(route('library.gallery.stream', [$album, $item]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }
}
