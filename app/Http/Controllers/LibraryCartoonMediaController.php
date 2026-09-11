<?php

namespace App\Http\Controllers;

use App\Models\Cartoon;
use App\Models\CartoonFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryCartoonMediaController extends Controller
{
    public function poster(Cartoon $cartoon): BinaryFileResponse
    {
        abort_unless($cartoon->hasPoster(), 404);

        $disk = Storage::disk((string) config('media.disk'));
        $path = $disk->path($cartoon->poster);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function stream(Cartoon $cartoon, CartoonFile $file): BinaryFileResponse
    {
        abort_unless($file->cartoon_id === $cartoon->id, 404);

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
