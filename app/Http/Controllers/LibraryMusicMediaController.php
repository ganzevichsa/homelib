<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\AlbumTrack;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryMusicMediaController extends Controller
{
    public function poster(Album $album): BinaryFileResponse
    {
        abort_unless($album->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($album->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Album $album, AlbumTrack $track): BinaryFileResponse
    {
        abort_unless($track->album_id === $album->id, 404);

        $disk = Storage::disk((string) config('media.disk'));

        abort_unless($disk->exists($track->path), 404);

        return response()->file($disk->path($track->path), [
            'Content-Type' => $track->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$track->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
