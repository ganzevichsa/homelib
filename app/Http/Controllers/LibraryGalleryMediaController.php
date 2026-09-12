<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryGalleryMediaController extends Controller
{
    public function poster(GalleryAlbum $galleryAlbum): BinaryFileResponse
    {
        if ($galleryAlbum->hasPoster()) {
            $disk = Storage::disk((string) config('media.disk'));
            $path = $disk->path($galleryAlbum->poster);

            return response()->file($path, [
                'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        $galleryAlbum->load('items');
        $cover = $galleryAlbum->coverItem();
        abort_unless($cover !== null, 404);

        return $this->stream($galleryAlbum, $cover);
    }

    public function stream(GalleryAlbum $galleryAlbum, GalleryItem $item): BinaryFileResponse
    {
        abort_unless($item->gallery_album_id === $galleryAlbum->id, 404);

        $disk = Storage::disk((string) config('media.disk'));
        abort_unless($disk->exists($item->path), 404);

        return response()->file($disk->path($item->path), [
            'Content-Type' => $item->browserMime(),
            'Content-Disposition' => 'inline; filename="'.$item->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => $item->isImage() ? 'public, max-age=86400' : 'private',
        ]);
    }
}
