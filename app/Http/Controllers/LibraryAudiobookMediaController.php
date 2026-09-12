<?php

namespace App\Http\Controllers;

use App\Models\Audiobook;
use App\Models\AudiobookChapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryAudiobookMediaController extends Controller
{
    public function poster(Audiobook $audiobook): BinaryFileResponse
    {
        abort_unless($audiobook->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($audiobook->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Audiobook $audiobook, AudiobookChapter $chapter): BinaryFileResponse
    {
        abort_unless($chapter->audiobook_id === $audiobook->id, 404);

        $disk = Storage::disk((string) config('media.disk'));
        abort_unless($disk->exists($chapter->path), 404);

        return response()->file($disk->path($chapter->path), [
            'Content-Type' => $chapter->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$chapter->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
