<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAudiobookChapterRequest;
use App\Models\Audiobook;
use App\Models\AudiobookChapter;
use App\Support\DiskLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AudiobookChapterController extends Controller
{
    public function create(Audiobook $audiobook): View
    {
        $library = new DiskLibrary('audiobooks', AudiobookChapter::class);

        return view('admin.audiobooks.chapters.create', [
            'audiobook' => $audiobook,
            'nextNumber' => (int) $audiobook->chapters()->max('number') + 1,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $library = new DiskLibrary('audiobooks', AudiobookChapter::class);

        return response()->json([
            'filenames' => $library->suggest(trim((string) $request->query('q', ''))),
        ]);
    }

    public function store(StoreAudiobookChapterRequest $request, Audiobook $audiobook): RedirectResponse
    {
        $library = new DiskLibrary('audiobooks', AudiobookChapter::class);

        $audiobook->chapters()->create([
            ...$library->attributes($request->validated('filename')),
            'number' => $request->validated('number'),
            'title' => $request->validated('title'),
        ]);

        return redirect()->route('admin.audiobooks.edit', $audiobook)->with('status', 'chapter-created');
    }

    public function destroy(Audiobook $audiobook, AudiobookChapter $chapter): RedirectResponse
    {
        abort_unless($chapter->audiobook_id === $audiobook->id, 404);
        $chapter->delete();

        return redirect()->route('admin.audiobooks.edit', $audiobook)->with('status', 'chapter-deleted');
    }
}
