<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Health endpoints
Route::get('/health', fn () => response('ok', 200));

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});