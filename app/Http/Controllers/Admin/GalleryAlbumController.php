<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryAlbumRequest;
use App\Models\GalleryAlbum;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'albums' => GalleryAlbum::query()->with('items')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(StoreGalleryAlbumRequest $request): RedirectResponse
    {
        $album = GalleryAlbum::query()->create($request->attributesForModel());
        $this->syncPoster($request, $album);

        return redirect()->route('admin.gallery.edit', $album)->with('status', 'created');
    }

    public function edit(GalleryAlbum $galleryAlbum): View
    {
        $galleryAlbum->load('items');

        return view('admin.gallery.edit', ['album' => $galleryAlbum]);
    }

    public function update(StoreGalleryAlbumRequest $request, GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $galleryAlbum->update($request->attributesForModel());
        $this->syncPoster($request, $galleryAlbum);

        return redirect()->route('admin.gallery.edit', $galleryAlbum)->with('status', 'updated');
    }

    public function destroy(GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $galleryAlbum->delete();

        return redirect()->route('admin.gallery')->with('status', 'deleted');
    }

    private function syncPoster(StoreGalleryAlbumRequest $request, GalleryAlbum $album): void
    {
        if ($request->boolean('remove_poster')) {
            $album->deletePosterFile();
            $album->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $album->storePoster($request->file('poster'));
        }
    }
}
