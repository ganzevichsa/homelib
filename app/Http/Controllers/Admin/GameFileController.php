<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachedFileRequest;
use App\Models\Game;
use App\Models\GameFile;
use App\Support\DiskLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameFileController extends Controller
{
    public function create(Game $game): View
    {
        $library = new DiskLibrary('games', GameFile::class);

        return view('admin.games.files.create', [
            'game' => $game,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $library = new DiskLibrary('games', GameFile::class);

        return response()->json([
            'filenames' => $library->suggest(trim((string) $request->query('q', ''))),
        ]);
    }

    public function store(StoreAttachedFileRequest $request, Game $game): RedirectResponse
    {
        $library = new DiskLibrary('games', GameFile::class);

        $game->files()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'sort_order' => (int) $game->files()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.games.edit', $game)->with('status', 'file-created');
    }

    public function destroy(Game $game, GameFile $file): RedirectResponse
    {
        abort_unless($file->game_id === $game->id, 404);
        $file->delete();

        return redirect()->route('admin.games.edit', $game)->with('status', 'file-deleted');
    }
}
