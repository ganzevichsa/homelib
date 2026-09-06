<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use App\Support\MovieLibrary;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(MovieLibrary $library): View
    {
        return view('admin.movies.index', [
            'movies' => Movie::query()->with('files')->latest()->get(),
            'availableFiles' => $library->unusedFiles(),
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function create(MovieLibrary $library): View
    {
        return view('admin.movies.create', [
            'files' => $library->unusedFiles(),
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function store(StoreMovieRequest $request, MovieLibrary $library): RedirectResponse
    {
        $movie = Movie::query()->create($request->movieAttributes());

        foreach ($request->selectedFiles() as $file) {
            $movie->files()->create([
                ...$library->attributes($file['filename']),
                'title' => $file['title'] ?: pathinfo($file['filename'], PATHINFO_FILENAME),
                'sort_order' => $file['sort_order'],
            ]);
        }

        return redirect()
            ->route('admin.movies')
            ->with('status', 'movie-created');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return redirect()
            ->route('admin.movies')
            ->with('status', 'movie-deleted');
    }
}
