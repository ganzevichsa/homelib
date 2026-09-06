<?php

use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\MovieFileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/library/movie/{movie}', [LibraryController::class, 'movie'])->name('library.movie');
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
        Route::view('/series', 'admin.category', ['title' => 'Сериалы'])->name('series');
        Route::view('/cartoons', 'admin.category', ['title' => 'Мультфильмы'])->name('cartoons');
        Route::view('/animated-series', 'admin.category', ['title' => 'Мультсериалы'])->name('animated-series');
        Route::view('/audio', 'admin.category', ['title' => 'Аудио'])->name('audio');
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
