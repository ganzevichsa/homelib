<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use App\Models\Season;
use App\Models\Series;
use Illuminate\Http\RedirectResponse;

class SeasonController extends Controller
{
    public function store(StoreSeasonRequest $request, Series $series): RedirectResponse
    {
        $series->seasons()->create($request->validated());

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'season-created');
    }

    public function destroy(Series $series, Season $season): RedirectResponse
    {
        abort_unless($season->series_id === $series->id, 404);

        $season->episodes()->delete();
        $season->delete();

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'season-deleted');
    }
}
