<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimatedSeasonRequest;
use App\Models\AnimatedSeason;
use App\Models\AnimatedSeries;
use Illuminate\Http\RedirectResponse;

class AnimatedSeasonController extends Controller
{
    public function store(StoreAnimatedSeasonRequest $request, AnimatedSeries $animatedSeries): RedirectResponse
    {
        $animatedSeries->seasons()->create($request->validated());

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'season-created');
    }

    public function destroy(AnimatedSeries $animatedSeries, AnimatedSeason $animatedSeason): RedirectResponse
    {
        abort_unless($animatedSeason->animated_series_id === $animatedSeries->id, 404);

        $animatedSeason->episodes()->delete();
        $animatedSeason->delete();

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'season-deleted');
    }
}
