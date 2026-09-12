<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryBookMediaController extends Controller
{
    public function poster(Book $book): BinaryFileResponse
    {
        abort_unless($book->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($book->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Book $book, BookFile $file): BinaryFileResponse
    {
        abort_unless($file->book_id === $book->id, 404);

        $disk = Storage::disk((string) config('media.disk'));

        abort_unless($disk->exists($file->path), 404);

        $disposition = $file->isBrowserReadable() ? 'inline' : 'attachment';

        return response()->file($disk->path($file->path), [
            'Content-Type' => $file->browserMime(),
            'Content-Disposition' => $disposition.'; filename="'.$file->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
