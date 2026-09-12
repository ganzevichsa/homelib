<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookFileRequest;
use App\Models\Book;
use App\Models\BookFile;
use App\Support\BookLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookFileController extends Controller
{
    public function create(Book $book, BookLibrary $library): View
    {
        return view('admin.books.files.create', [
            'book' => $book,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, BookLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(StoreBookFileRequest $request, Book $book, BookLibrary $library): RedirectResponse
    {
        $book->files()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'sort_order' => (int) $book->files()->max('sort_order') + 1,
        ]);

        return redirect()
            ->route('admin.books.edit', $book)
            ->with('status', 'book-file-created');
    }

    public function destroy(Book $book, BookFile $file): RedirectResponse
    {
        abort_unless($file->book_id === $book->id, 404);

        $file->delete();

        return redirect()
            ->route('admin.books.edit', $book)
            ->with('status', 'book-file-deleted');
    }
}
