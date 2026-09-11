<?php

namespace App\Http\Controllers;

use App\Models\AnimatedEpisode;
use App\Models\AnimatedSeries;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryAnimatedSeriesMediaController extends Controller
{
    public function poster(AnimatedSeries $animatedSeries): BinaryFileResponse
    {
        abort_unless($animatedSeries->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($animatedSeries->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(AnimatedSeries $animatedSeries, AnimatedEpisode $animatedEpisode): BinaryFileResponse
    {
        abort_unless($animatedEpisode->belongsToAnimatedSeries($animatedSeries), 404);

        $disk = Storage::disk((string) config('media.disk'));

        abort_unless($disk->exists($animatedEpisode->path), 404);

        return response()->file($disk->path($animatedEpisode->path), [
            'Content-Type' => $animatedEpisode->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$animatedEpisode->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
