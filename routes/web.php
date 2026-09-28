<?php

use Illuminate\Support\Facades\Route;

// Site editorial público (Blade, SSR)
Route::view('/', 'site.home')->name('home');

// No artigo o header não é fixo — apenas a barra de ferramentas de leitura fica sticky
Route::view('/artigo/{slug}', 'site.artigo', [
    'activeSection' => 'politica-economica',
    'stickyHeader' => false,
])->name('artigo');

Route::get('/categoria/{slug}', function (string $slug) {
    abort_unless(config()->has('sections.'.$slug), 404);

    return view('site.categoria', ['slug' => $slug, 'activeSection' => $slug]);
})->name('categoria');

Route::get('/pesquisa', fn () => view('site.pesquisa', ['q' => request('q', '')]))->name('pesquisa');

Route::get('/autor/{slug}', fn (string $slug) => view('site.autor', ['slug' => $slug]))->name('autor');

Route::view('/sobre', 'site.sobre')->name('sobre');
Route::view('/contactos', 'site.contactos')->name('contactos');

// Páginas institucionais (política editorial, privacidade, termos)
Route::get('/{page}', function (string $page) {
    $pages = ['politica-editorial', 'privacidade', 'termos'];
    abort_unless(in_array($page, $pages, true), 404);

    return view('site.institucional', ['page' => $page]);
})->whereIn('page', ['politica-editorial', 'privacidade', 'termos'])->name('pagina');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
