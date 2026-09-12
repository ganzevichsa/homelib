<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Audiobook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryAudiobookTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_an_audiobook(): void
    {
        $audiobook = Audiobook::factory()->create([
            'title' => [Locale::Ru->value => 'Мастер и Маргарита'],
            'author' => 'Булгаков',
            'narrator' => 'Иванов',
            'year' => 1967,
        ]);

        $audiobook->chapters()->create([
            'number' => 1,
            'title' => 'Глава 1',
            'filename' => '01-Chapter.mp3',
            'path' => 'audiobooks/01-Chapter.mp3',
            'extension' => 'mp3',
            'size' => 10,
        ]);

        $this->get(route('library.show', MediaType::Audiobook))
            ->assertOk()
            ->assertSee('Мастер и Маргарита')
            ->assertSee('Булгаков')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.audiobook', $audiobook))
            ->assertOk()
            ->assertSee('Мастер и Маргарита')
            ->assertSee('Глава 1')
            ->assertSee('Иванов')
            ->assertSee('<audio', false);
    }

    public function test_guest_can_stream_a_chapter(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('audiobooks/Chapter.mp3', 'audio-bytes');

        $audiobook = Audiobook::factory()->create();
        $chapter = $audiobook->chapters()->create([
            'number' => 1,
            'title' => 'Глава 1',
            'filename' => 'Chapter.mp3',
            'path' => 'audiobooks/Chapter.mp3',
            'extension' => 'mp3',
            'mime_type' => 'audio/mpeg',
            'size' => 11,
        ]);

        $this->get(route('library.audiobook.stream', [$audiobook, $chapter]))
            ->assertOk()
            ->assertHeader('Content-Type', 'audio/mpeg');
    }

    public function test_audiobook_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Audiobook))
            ->assertOk()
            ->assertSee('Аудиокниги')
            ->assertSee('Пока пусто');
    }
}
