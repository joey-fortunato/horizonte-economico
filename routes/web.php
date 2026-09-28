<?php

use Illuminate\Support\Facades\Route;

// Site editorial público (Blade, SSR)
Route::view('/', 'site.home')->name('home');

Route::view('/artigo/{slug}', 'site.artigo', ['activeSection' => 'politica-economica'])
    ->name('artigo');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
