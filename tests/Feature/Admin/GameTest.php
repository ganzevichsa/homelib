<?php

namespace Tests\Feature\Admin;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_game_and_attach_iso(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('games/Half-Life.iso', 'iso');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.games.store'), [
                'title' => 'Half-Life',
                'year' => 1998,
            ])
            ->assertRedirect();

        $game = Game::query()->first();
        $this->assertNotNull($game);
        $this->assertSame('Half-Life', $game->getTranslation('title', 'ru'));

        $this->actingAs($user)
            ->post(route('admin.games.files.store', $game), [
                'title' => 'ISO',
                'filename' => 'Half-Life.iso',
            ])
            ->assertRedirect(route('admin.games.edit', $game));

        $this->assertDatabaseHas('game_files', [
            'game_id' => $game->id,
            'filename' => 'Half-Life.iso',
            'title' => 'ISO',
        ]);
    }

    public function test_admin_games_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.games'))
            ->assertOk()
            ->assertSee('Игры')
            ->assertSee('Пока пусто');
    }
}
