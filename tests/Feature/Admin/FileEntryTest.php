<?php

namespace Tests\Feature\Admin;

use App\Models\FileEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_file_card_and_attach_documents(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('files/Contract.pdf', 'pdf');
        Storage::disk('media')->put('files/Notes.txt', 'txt');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.files.store'), [
                'title' => 'Договор',
                'description' => 'Скан договора',
            ])
            ->assertRedirect();

        $entry = FileEntry::query()->first();
        $this->assertNotNull($entry);

        $this->actingAs($user)
            ->post(route('admin.files.attachments.store', $entry), [
                'title' => 'PDF',
                'filename' => 'Contract.pdf',
            ])
            ->assertRedirect(route('admin.files.edit', $entry));

        $this->actingAs($user)
            ->post(route('admin.files.attachments.store', $entry), [
                'title' => 'Текст',
                'filename' => 'Notes.txt',
            ])
            ->assertRedirect(route('admin.files.edit', $entry));

        $this->assertSame(2, $entry->files()->count());
    }

    public function test_admin_files_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.files'))
            ->assertOk()
            ->assertSee('Файлы')
            ->assertSee('Пока пусто');
    }
}
