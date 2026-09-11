<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeriesRequest;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Series;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeriesController extends Controller
{
    public function index(): View
    {
        return view('admin.series.index', [
            'seriesList' => Series::query()
                ->with(['genres', 'countries', 'seasons.episodes'])
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.series.create', $this->catalog());
    }

    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        $series = Series::query()->create($request->seriesAttributes());
        $series->genres()->sync($request->genreIds());
        $series->countries()->sync($request->countryIds());
        $this->syncPoster($request, $series);

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'series-created');
    }

    public function edit(Series $series): View
    {
        $series->load(['genres', 'countries', 'seasons.episodes']);

        return view('admin.series.edit', [
            'series' => $series,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreSeriesRequest $request, Series $series): RedirectResponse
    {
        $series->update($request->seriesAttributes());
        $series->genres()->sync($request->genreIds());
        $series->countries()->sync($request->countryIds());
        $this->syncPoster($request, $series);

        return redirect()
            ->route('admin.series.edit', $series)
            ->with('status', 'series-updated');
    }

    public function destroy(Series $series): RedirectResponse
    {
        $series->delete();

        return redirect()
            ->route('admin.series')
            ->with('status', 'series-deleted');
    }

    private function syncPoster(StoreSeriesRequest $request, Series $series): void
    {
        if ($request->boolean('remove_poster')) {
            $series->deletePosterFile();
            $series->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $series->storePoster($request->file('poster'));
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
