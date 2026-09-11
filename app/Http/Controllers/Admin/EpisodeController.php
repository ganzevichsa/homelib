<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpisodeRequest;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use App\Support\SeriesLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EpisodeController extends Controller
{
    public function create(Series $series, Season $season, SeriesLibrary $library): View
    {
        abort_unless($season->series_id === $series->id, 404);

        return view('admin.series.episodes.create', [
            'series' => $series,
            'season' => $season,
            'nextNumber' => (int) $season->episodes()->max('number') + 1,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, SeriesLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(StoreEpisodeRequest $request, Series $series, Season $season, SeriesLibrary $library): RedirectResponse
    {
        abort_unless($season->series_id === $series->id, 404);

        $season->episodes()->create([
            ...$library->attributes($request->validated('filename')),
            'number' => $request->validated('number'),
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
        ]);

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'episode-created');
    }

    public function destroy(Series $series, Season $season, Episode $episode): RedirectResponse
    {
        abort_unless($season->series_id === $series->id, 404);
        abort_unless($episode->season_id === $season->id, 404);

        $episode->delete();

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'episode-deleted');
    }
}
