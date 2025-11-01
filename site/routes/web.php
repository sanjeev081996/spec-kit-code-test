<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\PackageController;
use App\Http\Controllers\Web\DestinationController;
use App\Http\Controllers\Web\GalleryController;
use App\Http\Controllers\Web\ContactController;

Route::get('/', HomeController::class);
Route::get('/about', AboutController::class);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{package:slug}', [PackageController::class, 'show']);
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show']);
Route::get('/gallery', GalleryController::class);
Route::get('/contact', ContactController::class);

// Health endpoints
Route::get('/health', fn () => response('ok', 200));

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
