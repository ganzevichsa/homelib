<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        return view('admin.books.index', [
            'books' => Book::query()->with(['files', 'genres'])->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', $this->catalog());
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $book = Book::query()->create($request->bookAttributes());
        $book->genres()->sync($request->genreIds());
        $this->syncPoster($request, $book);

        return redirect()
            ->route('admin.books.edit', $book)
            ->with('status', 'book-created');
    }

    public function edit(Book $book): View
    {
        $book->load(['files', 'genres']);

        return view('admin.books.edit', [
            'book' => $book,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreBookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->bookAttributes());
        $book->genres()->sync($request->genreIds());
        $this->syncPoster($request, $book);

        return redirect()
            ->route('admin.books.edit', $book)
            ->with('status', 'book-updated');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()
            ->route('admin.books')
            ->with('status', 'book-deleted');
    }

    private function syncPoster(StoreBookRequest $request, Book $book): void
    {
        if ($request->boolean('remove_poster')) {
            $book->deletePosterFile();
            $book->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $book->storePoster($request->file('poster'));
        }
    }

    /**
     * @return array{genres: Collection<int, Genre>}
     */
    private function catalog(): array
    {
        return [
            'genres' => Genre::query()->orderBy('name')->get(),
        ];
    }
}
