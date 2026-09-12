<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryGameMediaController extends Controller
{
    public function poster(Game $game): BinaryFileResponse
    {
        abort_unless($game->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($game->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function download(Game $game, GameFile $file): BinaryFileResponse
    {
        abort_unless($file->game_id === $game->id, 404);

        $disk = Storage::disk((string) config('media.disk'));
        abort_unless($disk->exists($file->path), 404);

        return response()->file($disk->path($file->path), [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$file->filename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
