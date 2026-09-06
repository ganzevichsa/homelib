<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/library/{type}', [LibraryController::class, 'show'])->name('library.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/movies', 'admin.category', ['title' => 'Фильмы'])->name('movies');
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
