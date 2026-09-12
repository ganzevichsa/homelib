<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Models\Album;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(): View
    {
        return view('admin.music.index', [
            'albums' => Album::query()->with(['tracks', 'genres'])->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.music.create', $this->catalog());
    }

    public function store(StoreAlbumRequest $request): RedirectResponse
    {
        $album = Album::query()->create($request->albumAttributes());
        $album->genres()->sync($request->genreIds());
        $this->syncPoster($request, $album);

        return redirect()
            ->route('admin.music.edit', $album)
            ->with('status', 'album-created');
    }

    public function edit(Album $album): View
    {
        $album->load(['tracks', 'genres']);

        return view('admin.music.edit', [
            'album' => $album,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreAlbumRequest $request, Album $album): RedirectResponse
    {
        $album->update($request->albumAttributes());
        $album->genres()->sync($request->genreIds());
        $this->syncPoster($request, $album);

        return redirect()
            ->route('admin.music.edit', $album)
            ->with('status', 'album-updated');
    }

    public function destroy(Album $album): RedirectResponse
    {
        $album->delete();

        return redirect()
            ->route('admin.music')
            ->with('status', 'album-deleted');
    }

    private function syncPoster(StoreAlbumRequest $request, Album $album): void
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

    /**
     * @return array{genres: Collection<int, Genre>}
     */
    private function catalog(): array
    {
        return [
            'genres' => Genre::query()->orderBy('name')->get(),
        ];
    }
}
