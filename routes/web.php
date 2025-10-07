<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LandingController;
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/{slug}', [NewsController::class, 'category'])->name('news.category');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/author/{username}', [AuthorController::class, 'show'])->name('author.show');
