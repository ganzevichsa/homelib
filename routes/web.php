<?php

use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\AlbumTrackController;
use App\Http\Controllers\Admin\AnimatedEpisodeController;
use App\Http\Controllers\Admin\AnimatedSeasonController;
use App\Http\Controllers\Admin\AnimatedSeriesController;
use App\Http\Controllers\Admin\CartoonController;
use App\Http\Controllers\Admin\CartoonFileController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\MovieFileController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryAnimatedSeriesMediaController;
use App\Http\Controllers\LibraryCartoonMediaController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\LibraryMovieMediaController;
use App\Http\Controllers\LibraryMusicMediaController;
use App\Http\Controllers\LibrarySeriesMediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/library/movie/{movie}/poster', [LibraryMovieMediaController::class, 'poster'])->name('library.movie.poster');
Route::get('/library/movie/{movie}/files/{file}/stream', [LibraryMovieMediaController::class, 'stream'])->name('library.movie.stream');
Route::get('/library/movie/{movie}', [LibraryController::class, 'movie'])->name('library.movie');
Route::get('/library/series/{series}/poster', [LibrarySeriesMediaController::class, 'poster'])->name('library.series.poster');
Route::get('/library/series/{series}/episodes/{episode}/stream', [LibrarySeriesMediaController::class, 'stream'])->name('library.series.stream');
Route::get('/library/series/{series}', [LibraryController::class, 'series'])->name('library.series');
Route::get('/library/cartoon/{cartoon}/poster', [LibraryCartoonMediaController::class, 'poster'])->name('library.cartoon.poster');
Route::get('/library/cartoon/{cartoon}/files/{file}/stream', [LibraryCartoonMediaController::class, 'stream'])->name('library.cartoon.stream');
Route::get('/library/cartoon/{cartoon}', [LibraryController::class, 'cartoon'])->name('library.cartoon');
Route::get('/library/animated_series/{animatedSeries}/poster', [LibraryAnimatedSeriesMediaController::class, 'poster'])->name('library.animated-series.poster');
Route::get('/library/animated_series/{animatedSeries}/episodes/{animatedEpisode}/stream', [LibraryAnimatedSeriesMediaController::class, 'stream'])->name('library.animated-series.stream');
Route::get('/library/animated_series/{animatedSeries}', [LibraryController::class, 'animatedSeries'])->name('library.animated-series');
Route::get('/library/music/{album}/poster', [LibraryMusicMediaController::class, 'poster'])->name('library.music.poster');
Route::get('/library/music/{album}/tracks/{track}/stream', [LibraryMusicMediaController::class, 'stream'])->name('library.music.stream');
Route::get('/library/music/{album}', [LibraryController::class, 'album'])->name('library.music');
Route::get('/library/{type}', [LibraryController::class, 'show'])->name('library.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/movies', [MovieController::class, 'index'])->name('movies');
        Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
        Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
        Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
        Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
        Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');
        Route::get('/movies/files/search', [MovieFileController::class, 'search'])->name('movies.files.search');
        Route::get('/movies/{movie}/files/create', [MovieFileController::class, 'create'])->name('movies.files.create');
        Route::post('/movies/{movie}/files', [MovieFileController::class, 'store'])->name('movies.files.store');
        Route::delete('/movies/{movie}/files/{file}', [MovieFileController::class, 'destroy'])->name('movies.files.destroy');
        Route::get('/series', [SeriesController::class, 'index'])->name('series');
        Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
        Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
        Route::get('/series/episodes/search', [EpisodeController::class, 'search'])->name('series.episodes.search');
        Route::get('/series/{series}/edit', [SeriesController::class, 'edit'])->name('series.edit');
        Route::put('/series/{series}', [SeriesController::class, 'update'])->name('series.update');
        Route::delete('/series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');
        Route::post('/series/{series}/seasons', [SeasonController::class, 'store'])->name('series.seasons.store');
        Route::delete('/series/{series}/seasons/{season}', [SeasonController::class, 'destroy'])->name('series.seasons.destroy');
        Route::get('/series/{series}/seasons/{season}/episodes/create', [EpisodeController::class, 'create'])->name('series.episodes.create');
        Route::post('/series/{series}/seasons/{season}/episodes', [EpisodeController::class, 'store'])->name('series.episodes.store');
        Route::delete('/series/{series}/seasons/{season}/episodes/{episode}', [EpisodeController::class, 'destroy'])->name('series.episodes.destroy');
        Route::get('/cartoons', [CartoonController::class, 'index'])->name('cartoons');
        Route::get('/cartoons/create', [CartoonController::class, 'create'])->name('cartoons.create');
        Route::post('/cartoons', [CartoonController::class, 'store'])->name('cartoons.store');
        Route::get('/cartoons/files/search', [CartoonFileController::class, 'search'])->name('cartoons.files.search');
        Route::get('/cartoons/{cartoon}/edit', [CartoonController::class, 'edit'])->name('cartoons.edit');
        Route::put('/cartoons/{cartoon}', [CartoonController::class, 'update'])->name('cartoons.update');
        Route::delete('/cartoons/{cartoon}', [CartoonController::class, 'destroy'])->name('cartoons.destroy');
        Route::get('/cartoons/{cartoon}/files/create', [CartoonFileController::class, 'create'])->name('cartoons.files.create');
        Route::post('/cartoons/{cartoon}/files', [CartoonFileController::class, 'store'])->name('cartoons.files.store');
        Route::delete('/cartoons/{cartoon}/files/{file}', [CartoonFileController::class, 'destroy'])->name('cartoons.files.destroy');
        Route::get('/animated-series', [AnimatedSeriesController::class, 'index'])->name('animated-series');
        Route::get('/animated-series/create', [AnimatedSeriesController::class, 'create'])->name('animated-series.create');
        Route::post('/animated-series', [AnimatedSeriesController::class, 'store'])->name('animated-series.store');
        Route::get('/animated-series/episodes/search', [AnimatedEpisodeController::class, 'search'])->name('animated-series.episodes.search');
        Route::get('/animated-series/{animatedSeries}/edit', [AnimatedSeriesController::class, 'edit'])->name('animated-series.edit');
        Route::put('/animated-series/{animatedSeries}', [AnimatedSeriesController::class, 'update'])->name('animated-series.update');
        Route::delete('/animated-series/{animatedSeries}', [AnimatedSeriesController::class, 'destroy'])->name('animated-series.destroy');
        Route::post('/animated-series/{animatedSeries}/seasons', [AnimatedSeasonController::class, 'store'])->name('animated-series.seasons.store');
        Route::delete('/animated-series/{animatedSeries}/seasons/{animatedSeason}', [AnimatedSeasonController::class, 'destroy'])->name('animated-series.seasons.destroy');
        Route::get('/animated-series/{animatedSeries}/seasons/{animatedSeason}/episodes/create', [AnimatedEpisodeController::class, 'create'])->name('animated-series.episodes.create');
        Route::post('/animated-series/{animatedSeries}/seasons/{animatedSeason}/episodes', [AnimatedEpisodeController::class, 'store'])->name('animated-series.episodes.store');
        Route::delete('/animated-series/{animatedSeries}/seasons/{animatedSeason}/episodes/{animatedEpisode}', [AnimatedEpisodeController::class, 'destroy'])->name('animated-series.episodes.destroy');
        Route::get('/music', [AlbumController::class, 'index'])->name('music');
        Route::get('/music/create', [AlbumController::class, 'create'])->name('music.create');
        Route::post('/music', [AlbumController::class, 'store'])->name('music.store');
        Route::get('/music/tracks/search', [AlbumTrackController::class, 'search'])->name('music.tracks.search');
        Route::get('/music/{album}/edit', [AlbumController::class, 'edit'])->name('music.edit');
        Route::put('/music/{album}', [AlbumController::class, 'update'])->name('music.update');
        Route::delete('/music/{album}', [AlbumController::class, 'destroy'])->name('music.destroy');
        Route::get('/music/{album}/tracks/create', [AlbumTrackController::class, 'create'])->name('music.tracks.create');
        Route::post('/music/{album}/tracks', [AlbumTrackController::class, 'store'])->name('music.tracks.store');
        Route::delete('/music/{album}/tracks/{track}', [AlbumTrackController::class, 'destroy'])->name('music.tracks.destroy');
        Route::view('/books', 'admin.category', ['title' => 'Книги'])->name('books');
        Route::view('/files', 'admin.category', ['title' => 'Файлы'])->name('files');
        Route::view('/other', 'admin.category', ['title' => 'Остальное'])->name('other');
        Route::view('/audiobooks', 'admin.category', ['title' => 'Аудиокниги'])->name('audiobooks');
        Route::view('/games', 'admin.category', ['title' => 'Игры'])->name('games');
        Route::view('/gallery', 'admin.category', ['title' => 'Галерея'])->name('gallery');
        Route::view('/documents', 'admin.category', ['title' => 'Документы'])->name('documents');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
