<?php

namespace Tests\Feature\Admin;

use App\Models\Audiobook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AudiobookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_audiobook_card_without_chapters(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.audiobooks.store'), [
                'title' => 'Мастер и Маргарита',
                'author' => 'Булгаков',
                'narrator' => 'Иванов',
                'year' => 1967,
            ])
            ->assertRedirect();

        $audiobook = Audiobook::query()->first();

        $this->assertNotNull($audiobook);
        $this->assertSame('Мастер и Маргарита', $audiobook->getTranslation('title', 'ru'));
        $this->assertSame('Булгаков', $audiobook->author);
        $this->assertSame(0, $audiobook->chapters()->count());

        $this->actingAs($user)
            ->get(route('admin.audiobooks.edit', $audiobook))
            ->assertOk()
            ->assertSee('Мастер и Маргарита')
            ->assertSee('Глав пока нет');
    }

    public function test_admin_adds_chapters_one_by_one(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('audiobooks/01-Chapter.mp3', 'one');
        Storage::disk('media')->put('audiobooks/02-Chapter.mp3', 'two');

        $user = User::factory()->create();
        $audiobook = Audiobook::factory()->create([
            'title' => ['ru' => 'Мастер и Маргарита'],
            'author' => 'Булгаков',
        ]);

        $this->actingAs($user)
            ->post(route('admin.audiobooks.chapters.store', $audiobook), [
                'number' => 1,
                'title' => 'Глава 1',
                'filename' => '01-Chapter.mp3',
            ])
            ->assertRedirect(route('admin.audiobooks.edit', $audiobook));

        $this->actingAs($user)
            ->post(route('admin.audiobooks.chapters.store', $audiobook), [
                'number' => 2,
                'title' => 'Глава 2',
                'filename' => '02-Chapter.mp3',
            ])
            ->assertRedirect(route('admin.audiobooks.edit', $audiobook));

        $this->assertSame(2, $audiobook->chapters()->count());
        $this->assertDatabaseHas('audiobook_chapters', [
            'audiobook_id' => $audiobook->id,
            'filename' => '01-Chapter.mp3',
            'title' => 'Глава 1',
            'number' => 1,
        ]);
    }

    public function test_admin_audiobooks_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.audiobooks'))
            ->assertOk()
            ->assertSee('Аудиокниги')
            ->assertSee('Пока пусто');
    }
}
