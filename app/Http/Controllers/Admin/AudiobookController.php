<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAudiobookRequest;
use App\Models\Audiobook;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AudiobookController extends Controller
{
    public function index(): View
    {
        return view('admin.audiobooks.index', [
            'audiobooks' => Audiobook::query()->with('chapters')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.audiobooks.create');
    }

    public function store(StoreAudiobookRequest $request): RedirectResponse
    {
        $audiobook = Audiobook::query()->create($request->attributesForModel());
        $this->syncPoster($request, $audiobook);

        return redirect()->route('admin.audiobooks.edit', $audiobook)->with('status', 'created');
    }

    public function edit(Audiobook $audiobook): View
    {
        $audiobook->load('chapters');

        return view('admin.audiobooks.edit', ['audiobook' => $audiobook]);
    }

    public function update(StoreAudiobookRequest $request, Audiobook $audiobook): RedirectResponse
    {
        $audiobook->update($request->attributesForModel());
        $this->syncPoster($request, $audiobook);

        return redirect()->route('admin.audiobooks.edit', $audiobook)->with('status', 'updated');
    }

    public function destroy(Audiobook $audiobook): RedirectResponse
    {
        $audiobook->delete();

        return redirect()->route('admin.audiobooks')->with('status', 'deleted');
    }

    private function syncPoster(StoreAudiobookRequest $request, Audiobook $audiobook): void
    {
        if ($request->boolean('remove_poster')) {
            $audiobook->deletePosterFile();
            $audiobook->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $audiobook->storePoster($request->file('poster'));
        }
    }
}
