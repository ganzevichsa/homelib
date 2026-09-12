<?php

namespace Tests\Feature\Admin;

use App\Models\Album;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_album_card_without_tracks(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.music.store'), [
                'title' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'year' => 1973,
            ])
            ->assertRedirect();

        $album = Album::query()->first();

        $this->assertNotNull($album);
        $this->assertSame('The Dark Side of the Moon', $album->getTranslation('title', 'ru'));
        $this->assertSame('Pink Floyd', $album->artist);
        $this->assertSame(0, $album->tracks()->count());

        $this->actingAs($user)
            ->get(route('admin.music.edit', $album))
            ->assertOk()
            ->assertSee('The Dark Side of the Moon')
            ->assertSee('Треков пока нет');
    }

    public function test_admin_adds_tracks_one_by_one(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('music/01-Speak-to-Me.mp3', 'one');
        Storage::disk('media')->put('music/02-Breathe.mp3', 'two');

        $user = User::factory()->create();
        $album = Album::factory()->create([
            'title' => ['ru' => 'The Dark Side of the Moon'],
            'artist' => 'Pink Floyd',
        ]);

        $this->actingAs($user)
            ->post(route('admin.music.tracks.store', $album), [
                'number' => 1,
                'title' => 'Speak to Me',
                'filename' => '01-Speak-to-Me.mp3',
            ])
            ->assertRedirect(route('admin.music.edit', $album));

        $this->actingAs($user)
            ->post(route('admin.music.tracks.store', $album), [
                'number' => 2,
                'title' => 'Breathe',
                'filename' => '02-Breathe.mp3',
            ])
            ->assertRedirect(route('admin.music.edit', $album));

        $this->assertSame(2, $album->tracks()->count());
        $this->assertDatabaseHas('album_tracks', [
            'album_id' => $album->id,
            'filename' => '01-Speak-to-Me.mp3',
            'title' => 'Speak to Me',
            'number' => 1,
        ]);
    }

    public function test_track_file_must_exist_in_music_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $album = Album::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.music.tracks.store', $album), [
                'number' => 1,
                'title' => 'Нет файла',
                'filename' => 'Missing.mp3',
            ])
            ->assertSessionHasErrors('filename');

        $this->assertSame(0, $album->tracks()->count());
    }

    public function test_admin_can_attach_genres(): void
    {
        $user = User::factory()->create();
        $rock = Genre::query()->create(['name' => 'Рок']);

        $this->actingAs($user)
            ->post(route('admin.music.store'), [
                'title' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'genre_ids' => [$rock->id],
            ])
            ->assertRedirect();

        $album = Album::query()->first();

        $this->assertNotNull($album);
        $this->assertEqualsCanonicalizing([$rock->id], $album->genres()->pluck('genres.id')->all());
    }

    public function test_track_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('music/Speak-to-Me.mp3', 'one');
        Storage::disk('media')->put('music/Money.flac', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.music.tracks.search', ['q' => 'speak']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['Speak-to-Me.mp3'],
            ]);
    }

    public function test_admin_can_upload_a_cover(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.music.store'), [
                'title' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'poster' => UploadedFile::fake()->image('cover.jpg', 600, 600),
            ])
            ->assertRedirect();

        $album = Album::query()->first();

        $this->assertNotNull($album?->poster);
        Storage::disk('media')->assertExists($album->poster);
    }

    public function test_admin_music_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.music'))
            ->assertOk()
            ->assertSee('Музыка')
            ->assertSee('Пока пусто');
    }
}
