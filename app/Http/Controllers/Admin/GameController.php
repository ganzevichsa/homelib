<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameRequest;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): View
    {
        return view('admin.games.index', [
            'games' => Game::query()->with('files')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.games.create');
    }

    public function store(StoreGameRequest $request): RedirectResponse
    {
        $game = Game::query()->create($request->attributesForModel());
        $this->syncPoster($request, $game);

        return redirect()->route('admin.games.edit', $game)->with('status', 'created');
    }

    public function edit(Game $game): View
    {
        $game->load('files');

        return view('admin.games.edit', ['game' => $game]);
    }

    public function update(StoreGameRequest $request, Game $game): RedirectResponse
    {
        $game->update($request->attributesForModel());
        $this->syncPoster($request, $game);

        return redirect()->route('admin.games.edit', $game)->with('status', 'updated');
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()->route('admin.games')->with('status', 'deleted');
    }

    private function syncPoster(StoreGameRequest $request, Game $game): void
    {
        if ($request->boolean('remove_poster')) {
            $game->deletePosterFile();
            $game->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $game->storePoster($request->file('poster'));
        }
    }
}
