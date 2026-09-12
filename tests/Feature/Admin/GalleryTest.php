<?php

namespace Tests\Feature\Admin;

use App\Models\GalleryAlbum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_album_and_attach_photo_and_video(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('gallery/Vacation.jpg', 'photo');
        Storage::disk('media')->put('gallery/Trip.mp4', 'video');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.gallery.store'), [
                'title' => 'Отпуск',
                'year' => 2024,
            ])
            ->assertRedirect();

        $album = GalleryAlbum::query()->first();
        $this->assertNotNull($album);

        $this->actingAs($user)
            ->post(route('admin.gallery.items.store', $album), [
                'title' => 'Пляж',
                'filename' => 'Vacation.jpg',
            ])
            ->assertRedirect(route('admin.gallery.edit', $album));

        $this->actingAs($user)
            ->post(route('admin.gallery.items.store', $album), [
                'filename' => 'Trip.mp4',
            ])
            ->assertRedirect(route('admin.gallery.edit', $album));

        $this->assertSame(2, $album->items()->count());
        $this->assertTrue($album->items->first()->isImage());
        $this->assertTrue($album->items->last()->isVideo());
    }

    public function test_admin_gallery_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.gallery'))
            ->assertOk()
            ->assertSee('Галерея')
            ->assertSee('Пока пусто');
    }
}
