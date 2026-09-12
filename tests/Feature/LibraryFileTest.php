<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\FileEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_file_entry(): void
    {
        $entry = FileEntry::factory()->create([
            'title' => [Locale::Ru->value => 'Договор'],
        ]);

        $entry->files()->create([
            'title' => 'PDF',
            'filename' => 'Contract.pdf',
            'path' => 'files/Contract.pdf',
            'extension' => 'pdf',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::File))
            ->assertOk()
            ->assertSee('Договор')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.file', $entry))
            ->assertOk()
            ->assertSee('Договор')
            ->assertSee('PDF')
            ->assertSee('<iframe', false);
    }

    public function test_guest_can_stream_a_pdf(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('files/Contract.pdf', 'pdf-bytes');

        $entry = FileEntry::factory()->create();
        $file = $entry->files()->create([
            'title' => 'PDF',
            'filename' => 'Contract.pdf',
            'path' => 'files/Contract.pdf',
            'extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'size' => 9,
            'sort_order' => 0,
        ]);

        $this->get(route('library.file.stream', [$entry, $file]))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }
}
