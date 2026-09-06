<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Models\Movie;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function show(MediaType $type): View
    {
        if ($type === MediaType::Movie) {
            return view('library.movies.index', [
                'type' => $type,
                'movies' => Movie::query()->with('files')->latest()->get(),
            ]);
        }

        return view('library.show', [
            'type' => $type,
        ]);
    }

    public function movie(Movie $movie): View
    {
        $movie->load(['files', 'genres', 'countries']);

        return view('library.movies.show', [
            'movie' => $movie,
        ]);
    }
}
