<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimatedEpisodeRequest;
use App\Models\AnimatedEpisode;
use App\Models\AnimatedSeason;
use App\Models\AnimatedSeries;
use App\Support\AnimatedSeriesLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnimatedEpisodeController extends Controller
{
    public function create(AnimatedSeries $animatedSeries, AnimatedSeason $animatedSeason, AnimatedSeriesLibrary $library): View
    {
        abort_unless($animatedSeason->animated_series_id === $animatedSeries->id, 404);

        return view('admin.animated-series.episodes.create', [
            'animatedSeries' => $animatedSeries,
            'season' => $animatedSeason,
            'nextNumber' => (int) $animatedSeason->episodes()->max('number') + 1,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, AnimatedSeriesLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(
        StoreAnimatedEpisodeRequest $request,
        AnimatedSeries $animatedSeries,
        AnimatedSeason $animatedSeason,
        AnimatedSeriesLibrary $library,
    ): RedirectResponse {
        abort_unless($animatedSeason->animated_series_id === $animatedSeries->id, 404);

        $animatedSeason->episodes()->create([
            ...$library->attributes($request->validated('filename')),
            'number' => $request->validated('number'),
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
        ]);

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'episode-created');
    }

    public function destroy(
        AnimatedSeries $animatedSeries,
        AnimatedSeason $animatedSeason,
        AnimatedEpisode $animatedEpisode,
    ): RedirectResponse {
        abort_unless($animatedSeason->animated_series_id === $animatedSeries->id, 404);
        abort_unless($animatedEpisode->animated_season_id === $animatedSeason->id, 404);

        $animatedEpisode->delete();

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'episode-deleted');
    }
}
