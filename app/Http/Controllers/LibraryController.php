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
                'movies' => Movie::query()
                    ->with(['files', 'genres', 'countries'])
                    ->latest()
                    ->get(),
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
            'playlist' => $movie->files->map(fn ($file): array => [
                'id' => $file->id,
                'title' => $file->title ?: $file->filename,
                'year' => $file->year,
                'description' => $file->description,
                'extension' => $file->extension,
                'playable' => $file->isBrowserPlayable(),
                'mime' => $file->browserMime(),
                'src' => route('library.movie.stream', [$movie, $file]),
            ])->values(),
        ]);
    }
}
