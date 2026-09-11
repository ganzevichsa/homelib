<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimatedSeriesRequest;
use App\Models\AnimatedSeries;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnimatedSeriesController extends Controller
{
    public function index(): View
    {
        return view('admin.animated-series.index', [
            'seriesList' => AnimatedSeries::query()
                ->with(['genres', 'countries', 'seasons.episodes'])
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.animated-series.create', $this->catalog());
    }

    public function store(StoreAnimatedSeriesRequest $request): RedirectResponse
    {
        $animatedSeries = AnimatedSeries::query()->create($request->seriesAttributes());
        $animatedSeries->genres()->sync($request->genreIds());
        $animatedSeries->countries()->sync($request->countryIds());
        $this->syncPoster($request, $animatedSeries);

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'animated-series-created');
    }

    public function edit(AnimatedSeries $animatedSeries): View
    {
        $animatedSeries->load(['genres', 'countries', 'seasons.episodes']);

        return view('admin.animated-series.edit', [
            'animatedSeries' => $animatedSeries,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreAnimatedSeriesRequest $request, AnimatedSeries $animatedSeries): RedirectResponse
    {
        $animatedSeries->update($request->seriesAttributes());
        $animatedSeries->genres()->sync($request->genreIds());
        $animatedSeries->countries()->sync($request->countryIds());
        $this->syncPoster($request, $animatedSeries);

        return redirect()
            ->route('admin.animated-series.edit', $animatedSeries)
            ->with('status', 'animated-series-updated');
    }

    public function destroy(AnimatedSeries $animatedSeries): RedirectResponse
    {
        $animatedSeries->delete();

        return redirect()
            ->route('admin.animated-series')
            ->with('status', 'animated-series-deleted');
    }

    private function syncPoster(StoreAnimatedSeriesRequest $request, AnimatedSeries $animatedSeries): void
    {
        if ($request->boolean('remove_poster')) {
            $animatedSeries->deletePosterFile();
            $animatedSeries->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $animatedSeries->storePoster($request->file('poster'));
        }
    }

    /**
     * @return array{genres: Collection<int, Genre>, countries: Collection<int, Country>}
     */
    private function catalog(): array
    {
        return [
            'genres' => Genre::query()->orderBy('name')->get(),
            'countries' => Country::query()->orderBy('name')->get(),
        ];
    }
}
