<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book_card_without_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.books.store'), [
                'title' => 'Война и мир',
                'author' => 'Лев Толстой',
                'year' => 1869,
            ])
            ->assertRedirect();

        $book = Book::query()->first();

        $this->assertNotNull($book);
        $this->assertSame('Война и мир', $book->getTranslation('title', 'ru'));
        $this->assertSame('Лев Толстой', $book->author);
        $this->assertSame(0, $book->files()->count());

        $this->actingAs($user)
            ->get(route('admin.books.edit', $book))
            ->assertOk()
            ->assertSee('Война и мир')
            ->assertSee('Файлов пока нет');
    }

    public function test_admin_adds_book_files_one_by_one(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('books/War.and.Peace.pdf', 'one');
        Storage::disk('media')->put('books/War.and.Peace.epub', 'two');

        $user = User::factory()->create();
        $book = Book::factory()->create([
            'title' => ['ru' => 'Война и мир'],
            'author' => 'Лев Толстой',
        ]);

        $this->actingAs($user)
            ->post(route('admin.books.files.store', $book), [
                'title' => 'PDF',
                'filename' => 'War.and.Peace.pdf',
            ])
            ->assertRedirect(route('admin.books.edit', $book));

        $this->actingAs($user)
            ->post(route('admin.books.files.store', $book), [
                'title' => 'EPUB',
                'filename' => 'War.and.Peace.epub',
            ])
            ->assertRedirect(route('admin.books.edit', $book));

        $this->assertSame(2, $book->files()->count());
        $this->assertDatabaseHas('book_files', [
            'book_id' => $book->id,
            'filename' => 'War.and.Peace.pdf',
            'title' => 'PDF',
        ]);
    }

    public function test_file_must_exist_in_books_folder(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.books.files.store', $book), [
                'title' => 'Нет файла',
                'filename' => 'Missing.pdf',
            ])
            ->assertSessionHasErrors('filename');

        $this->assertSame(0, $book->files()->count());
    }

    public function test_admin_can_attach_genres(): void
    {
        $user = User::factory()->create();
        $prose = Genre::query()->create(['name' => 'Проза']);

        $this->actingAs($user)
            ->post(route('admin.books.store'), [
                'title' => 'Война и мир',
                'author' => 'Лев Толстой',
                'genre_ids' => [$prose->id],
            ])
            ->assertRedirect();

        $book = Book::query()->first();

        $this->assertNotNull($book);
        $this->assertEqualsCanonicalizing([$prose->id], $book->genres()->pluck('genres.id')->all());
    }

    public function test_file_search_returns_matching_unattached_names(): void
    {
        Storage::fake('media');
        Storage::disk('media')->put('books/War.and.Peace.pdf', 'one');
        Storage::disk('media')->put('books/Anna.Karenina.epub', 'two');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.books.files.search', ['q' => 'war']))
            ->assertOk()
            ->assertExactJson([
                'filenames' => ['War.and.Peace.pdf'],
            ]);
    }

    public function test_admin_can_upload_a_cover(): void
    {
        Storage::fake('media');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.books.store'), [
                'title' => 'Война и мир',
                'author' => 'Лев Толстой',
                'poster' => UploadedFile::fake()->image('cover.jpg', 400, 600),
            ])
            ->assertRedirect();

        $book = Book::query()->first();

        $this->assertNotNull($book?->poster);
        Storage::disk('media')->assertExists($book->poster);
    }

    public function test_admin_books_index_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.books'))
            ->assertOk()
            ->assertSee('Книги')
            ->assertSee('Пока пусто');
    }
}
