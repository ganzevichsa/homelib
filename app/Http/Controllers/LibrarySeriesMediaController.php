<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Series;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibrarySeriesMediaController extends Controller
{
    public function poster(Series $series): BinaryFileResponse
    {
        abort_unless($series->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($series->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Series $series, Episode $episode): BinaryFileResponse
    {
        abort_unless($episode->belongsToSeries($series), 404);

        $disk = Storage::disk((string) config('media.disk'));

        abort_unless($disk->exists($episode->path), 404);

        return response()->file($disk->path($episode->path), [
            'Content-Type' => $episode->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$episode->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
