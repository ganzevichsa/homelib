<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Models\Album;
use App\Models\AnimatedSeries;
use App\Models\Book;
use App\Models\Cartoon;
use App\Models\Movie;
use App\Models\Series;
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

        if ($type === MediaType::Series) {
            return view('library.series.index', [
                'type' => $type,
                'seriesList' => Series::query()
                    ->with(['genres', 'countries', 'seasons.episodes'])
                    ->latest()
                    ->get(),
            ]);
        }

        if ($type === MediaType::Cartoon) {
            return view('library.cartoons.index', [
                'type' => $type,
                'cartoons' => Cartoon::query()
                    ->with(['files', 'genres', 'countries'])
                    ->latest()
                    ->get(),
            ]);
        }

        if ($type === MediaType::AnimatedSeries) {
            return view('library.animated-series.index', [
                'type' => $type,
                'seriesList' => AnimatedSeries::query()
                    ->with(['genres', 'countries', 'seasons.episodes'])
                    ->latest()
                    ->get(),
            ]);
        }

        if ($type === MediaType::Music) {
            return view('library.music.index', [
                'type' => $type,
                'albums' => Album::query()
                    ->with(['tracks', 'genres'])
                    ->latest()
                    ->get(),
            ]);
        }

        if ($type === MediaType::Book) {
            return view('library.books.index', [
                'type' => $type,
                'books' => Book::query()
                    ->with(['files', 'genres'])
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

    public function series(Series $series): View
    {
        $series->load(['genres', 'countries', 'seasons.episodes']);

        return view('library.series.show', [
            'series' => $series,
            'playlist' => $series->seasons
                ->flatMap(fn ($season) => $season->episodes->map(fn ($episode): array => [
                    'id' => $episode->id,
                    'title' => $episode->title,
                    'description' => $episode->description,
                    'extension' => $episode->extension,
                    'playable' => $episode->isBrowserPlayable(),
                    'mime' => $episode->browserMime(),
                    'src' => route('library.series.stream', [$series, $episode]),
                ]))
                ->values(),
        ]);
    }

    public function cartoon(Cartoon $cartoon): View
    {
        $cartoon->load(['files', 'genres', 'countries']);

        return view('library.cartoons.show', [
            'cartoon' => $cartoon,
            'playlist' => $cartoon->files->map(fn ($file): array => [
                'id' => $file->id,
                'title' => $file->title ?: $file->filename,
                'year' => $file->year,
                'description' => $file->description,
                'extension' => $file->extension,
                'playable' => $file->isBrowserPlayable(),
                'mime' => $file->browserMime(),
                'src' => route('library.cartoon.stream', [$cartoon, $file]),
            ])->values(),
        ]);
    }

    public function animatedSeries(AnimatedSeries $animatedSeries): View
    {
        $animatedSeries->load(['genres', 'countries', 'seasons.episodes']);

        return view('library.animated-series.show', [
            'animatedSeries' => $animatedSeries,
            'playlist' => $animatedSeries->seasons
                ->flatMap(fn ($season) => $season->episodes->map(fn ($episode): array => [
                    'id' => $episode->id,
                    'title' => $episode->title,
                    'description' => $episode->description,
                    'extension' => $episode->extension,
                    'playable' => $episode->isBrowserPlayable(),
                    'mime' => $episode->browserMime(),
                    'src' => route('library.animated-series.stream', [$animatedSeries, $episode]),
                ]))
                ->values(),
        ]);
    }

    public function album(Album $album): View
    {
        $album->load(['tracks', 'genres']);

        return view('library.music.show', [
            'album' => $album,
            'playlist' => $album->tracks->map(fn ($track): array => [
                'id' => $track->id,
                'number' => $track->number,
                'title' => $track->title,
                'extension' => $track->extension,
                'playable' => $track->isBrowserPlayable(),
                'mime' => $track->browserMime(),
                'src' => route('library.music.stream', [$album, $track]),
            ])->values(),
        ]);
    }

    public function book(Book $book): View
    {
        $book->load(['files', 'genres']);

        return view('library.books.show', [
            'book' => $book,
            'playlist' => $book->files->map(fn ($file): array => [
                'id' => $file->id,
                'title' => $file->title ?: $file->filename,
                'extension' => $file->extension,
                'readable' => $file->isBrowserReadable(),
                'src' => route('library.book.stream', [$book, $file]),
            ])->values(),
        ]);
    }
}
