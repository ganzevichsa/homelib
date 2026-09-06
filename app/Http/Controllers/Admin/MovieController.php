<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        return view('admin.movies.index', [
            'movies' => Movie::query()->with('files')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.movies.create');
    }

    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $movie = Movie::query()->create($request->movieAttributes());

        return redirect()
            ->route('admin.movies.edit', $movie)
            ->with('status', 'movie-created');
    }

    public function edit(Movie $movie): View
    {
        $movie->load('files');

        return view('admin.movies.edit', [
            'movie' => $movie,
        ]);
    }

    public function update(StoreMovieRequest $request, Movie $movie): RedirectResponse
    {
        $movie->update($request->movieAttributes());

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
}
