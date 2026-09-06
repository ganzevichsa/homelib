<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryMovieMediaController extends Controller
{
    public function poster(Movie $movie): BinaryFileResponse
    {
        abort_unless($movie->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($movie->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Movie $movie, MovieFile $file): BinaryFileResponse
    {
        abort_unless($file->movie_id === $movie->id, 404);

        $disk = Storage::disk((string) config('media.disk'));

        abort_unless($disk->exists($file->path), 404);

        return response()->file($disk->path($file->path), [
            'Content-Type' => $file->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$file->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
