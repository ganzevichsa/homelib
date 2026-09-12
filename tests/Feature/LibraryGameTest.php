<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryGameTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_game(): void
    {
        $game = Game::factory()->create([
            'title' => [Locale::Ru->value => 'Half-Life'],
            'year' => 1998,
        ]);

        $game->files()->create([
            'title' => 'ISO',
            'filename' => 'Half-Life.iso',
            'path' => 'games/Half-Life.iso',
            'extension' => 'iso',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::Game))
            ->assertOk()
            ->assertSee('Half-Life')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.game', $game))
            ->assertOk()
            ->assertSee('Half-Life')
            ->assertSee('ISO')
            ->assertDontSee('<audio', false)
            ->assertDontSee('<video', false);
    }

    public function test_guest_can_download_an_iso(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('games/Half-Life.iso', 'iso-bytes');

        $game = Game::factory()->create();
        $file = $game->files()->create([
            'title' => 'ISO',
            'filename' => 'Half-Life.iso',
            'path' => 'games/Half-Life.iso',
            'extension' => 'iso',
            'mime_type' => 'application/x-iso9660-image',
            'size' => 9,
            'sort_order' => 0,
        ]);

        $this->get(route('library.game.download', [$game, $file]))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="Half-Life.iso"');
    }
}
