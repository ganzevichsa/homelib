<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        return view('admin.movies.index', [
            'movies' => Movie::query()->with(['files', 'genres', 'countries'])->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.movies.create', $this->catalog());
    }

    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $movie = Movie::query()->create($request->movieAttributes());
        $movie->genres()->sync($request->genreIds());
        $movie->countries()->sync($request->countryIds());

        return redirect()
            ->route('admin.movies.edit', $movie)
            ->with('status', 'movie-created');
    }

    public function edit(Movie $movie): View
    {
        $movie->load(['files', 'genres', 'countries']);

        return view('admin.movies.edit', [
            'movie' => $movie,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreMovieRequest $request, Movie $movie): RedirectResponse
    {
        $movie->update($request->movieAttributes());
        $movie->genres()->sync($request->genreIds());
        $movie->countries()->sync($request->countryIds());

        return redirect()
            ->route('admin.movies.edit', $movie)
            ->with('status', 'movie-updated');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return redirect()
            ->route('admin.movies')
            ->with('status', 'movie-deleted');
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
