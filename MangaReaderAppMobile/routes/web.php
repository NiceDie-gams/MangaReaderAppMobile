<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TitleController::class, 'index'])->name('home');
Route::get('/title/{title:slug}', [TitleController::class, 'show'])->name('titles.show');
Route::get('/title/{title:slug}/chapter/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');

Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/favorites/{title}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{title}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('users.show');
});
