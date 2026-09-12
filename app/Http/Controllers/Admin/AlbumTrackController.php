<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumTrackRequest;
use App\Models\Album;
use App\Models\AlbumTrack;
use App\Support\MusicLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlbumTrackController extends Controller
{
    public function create(Album $album, MusicLibrary $library): View
    {
        return view('admin.music.tracks.create', [
            'album' => $album,
            'nextNumber' => (int) $album->tracks()->max('number') + 1,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, MusicLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(StoreAlbumTrackRequest $request, Album $album, MusicLibrary $library): RedirectResponse
    {
        $album->tracks()->create([
            ...$library->attributes($request->validated('filename')),
            'number' => $request->validated('number'),
            'title' => $request->validated('title'),
        ]);

        return redirect()
            ->route('admin.music.edit', $album)
            ->with('status', 'track-created');
    }

    public function destroy(Album $album, AlbumTrack $track): RedirectResponse
    {
        abort_unless($track->album_id === $album->id, 404);

        $track->delete();

        return redirect()
            ->route('admin.music.edit', $album)
            ->with('status', 'track-deleted');
    }
}
