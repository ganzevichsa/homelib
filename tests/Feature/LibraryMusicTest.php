<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Album;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryMusicTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_an_album_with_tracks(): void
    {
        $album = Album::factory()->create([
            'title' => [
                Locale::Ru->value => 'The Dark Side of the Moon',
            ],
            'artist' => 'Pink Floyd',
            'year' => 1973,
        ]);

        $album->genres()->attach(Genre::query()->create(['name' => 'Рок']));

        $album->tracks()->create([
            'number' => 1,
            'title' => 'Speak to Me',
            'filename' => '01-Speak-to-Me.mp3',
            'path' => 'music/01-Speak-to-Me.mp3',
            'extension' => 'mp3',
            'size' => 10,
        ]);

        $this->get(route('library.show', MediaType::Music))
            ->assertOk()
            ->assertSee('The Dark Side of the Moon')
            ->assertSee('Pink Floyd')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.music', $album))
            ->assertOk()
            ->assertSee('The Dark Side of the Moon')
            ->assertSee('Pink Floyd')
            ->assertSee('Speak to Me')
            ->assertSee('Рок')
            ->assertSee('<audio', false);
    }

    public function test_guest_can_stream_a_track(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('music/Track.mp3', 'audio-bytes');

        $album = Album::factory()->create();
        $track = $album->tracks()->create([
            'number' => 1,
            'title' => 'Speak to Me',
            'filename' => 'Track.mp3',
            'path' => 'music/Track.mp3',
            'extension' => 'mp3',
            'mime_type' => 'audio/mpeg',
            'size' => 11,
        ]);

        $this->get(route('library.music.stream', [$album, $track]))
            ->assertOk()
            ->assertHeader('Content-Type', 'audio/mpeg');
    }

    public function test_cannot_stream_track_from_another_album(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('music/Track.mp3', 'audio-bytes');

        $album = Album::factory()->create();
        $other = Album::factory()->create();
        $track = $other->tracks()->create([
            'number' => 1,
            'title' => 'Чужой трек',
            'filename' => 'Track.mp3',
            'path' => 'music/Track.mp3',
            'extension' => 'mp3',
            'size' => 11,
        ]);

        $this->get(route('library.music.stream', [$album, $track]))
            ->assertNotFound();
    }

    public function test_music_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Music))
            ->assertOk()
            ->assertSee('Музыка')
            ->assertSee('Пока пусто');
    }
}
