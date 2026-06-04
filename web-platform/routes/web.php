<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/explore', [PageController::class, 'explore'])->name('explore');
Route::get('/course/{id}', [PageController::class, 'courseDetail'])->name('course.detail');

Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/roadmap', [PageController::class, 'roadmap'])->name('roadmap');
Route::get('/my-learning', [PageController::class, 'myLearning'])->name('my-learning');

Route::get('/preferences', [RecommendationController::class, 'create'])->name('preferences.create');
Route::post('/preferences', [RecommendationController::class, 'store'])->name('preferences.store');

    