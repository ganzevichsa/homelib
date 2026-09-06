<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieFileRequest;
use App\Models\Movie;
use App\Models\MovieFile;
use App\Support\MovieLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieFileController extends Controller
{
    public function create(Movie $movie, MovieLibrary $library): View
    {
        return view('admin.movies.files.create', [
            'movie' => $movie,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, MovieLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(StoreMovieFileRequest $request, Movie $movie, MovieLibrary $library): RedirectResponse
    {
        $movie->files()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'year' => $request->validated('year'),
            'sort_order' => (int) $movie->files()->max('sort_order') + 1,
        ]);

        return redirect()
            ->route('admin.movies.edit', $movie)
            ->with('status', 'movie-file-created');
    }

    public function destroy(Movie $movie, MovieFile $file): RedirectResponse
    {
        abort_unless($file->movie_id === $movie->id, 404);

        $file->delete();

        return redirect()
            ->route('admin.movies.edit', $movie)
            ->with('status', 'movie-file-deleted');
    }
}
