<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryItemRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Support\DiskLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryItemController extends Controller
{
    public function create(GalleryAlbum $galleryAlbum): View
    {
        $library = new DiskLibrary('gallery', GalleryItem::class);

        return view('admin.gallery.items.create', [
            'album' => $galleryAlbum,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $library = new DiskLibrary('gallery', GalleryItem::class);

        return response()->json([
            'filenames' => $library->suggest(trim((string) $request->query('q', ''))),
        ]);
    }

    public function store(StoreGalleryItemRequest $request, GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $library = new DiskLibrary('gallery', GalleryItem::class);

        $galleryAlbum->items()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'sort_order' => (int) $galleryAlbum->items()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.gallery.edit', $galleryAlbum)->with('status', 'item-created');
    }

    public function destroy(GalleryAlbum $galleryAlbum, GalleryItem $item): RedirectResponse
    {
        abort_unless($item->gallery_album_id === $galleryAlbum->id, 404);
        $item->delete();

        return redirect()->route('admin.gallery.edit', $galleryAlbum)->with('status', 'item-deleted');
    }
}
