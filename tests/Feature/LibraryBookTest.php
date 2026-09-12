<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Enums\MediaType;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LibraryBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_lists_and_shows_a_book_with_files(): void
    {
        $book = Book::factory()->create([
            'title' => [
                Locale::Ru->value => 'Война и мир',
            ],
            'author' => 'Лев Толстой',
            'year' => 1869,
        ]);

        $book->genres()->attach(Genre::query()->create(['name' => 'Проза']));

        $book->files()->create([
            'title' => 'PDF',
            'filename' => 'War.and.Peace.pdf',
            'path' => 'books/War.and.Peace.pdf',
            'extension' => 'pdf',
            'size' => 10,
            'sort_order' => 0,
        ]);

        $this->get(route('library.show', MediaType::Book))
            ->assertOk()
            ->assertSee('Война и мир')
            ->assertSee('Лев Толстой')
            ->assertDontSee('Пока пусто');

        $this->get(route('library.book', $book))
            ->assertOk()
            ->assertSee('Война и мир')
            ->assertSee('Лев Толстой')
            ->assertSee('PDF')
            ->assertSee('Проза')
            ->assertSee('<iframe', false);
    }

    public function test_guest_can_stream_a_pdf(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('books/Book.pdf', 'pdf-bytes');

        $book = Book::factory()->create();
        $file = $book->files()->create([
            'title' => 'PDF',
            'filename' => 'Book.pdf',
            'path' => 'books/Book.pdf',
            'extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'size' => 9,
            'sort_order' => 0,
        ]);

        $this->get(route('library.book.stream', [$book, $file]))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cannot_stream_file_from_another_book(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('books/Book.pdf', 'pdf-bytes');

        $book = Book::factory()->create();
        $other = Book::factory()->create();
        $file = $other->files()->create([
            'title' => 'Чужой файл',
            'filename' => 'Book.pdf',
            'path' => 'books/Book.pdf',
            'extension' => 'pdf',
            'size' => 9,
            'sort_order' => 0,
        ]);

        $this->get(route('library.book.stream', [$book, $file]))
            ->assertNotFound();
    }

    public function test_book_category_page_is_empty_placeholder(): void
    {
        $this->get(route('library.show', MediaType::Book))
            ->assertOk()
            ->assertSee('Книги')
            ->assertSee('Пока пусто');
    }
}
