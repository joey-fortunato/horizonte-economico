<?php

use App\Http\Controllers\Site\ArticleController;
use App\Http\Controllers\Site\AuthorController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Route;

// SEO técnico
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Site editorial público (Blade, SSR)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artigo/{slug}', [ArticleController::class, 'show'])->name('artigo');
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('categoria');
Route::get('/pesquisa', [SearchController::class, 'index'])->name('pesquisa');
Route::post('/newsletter', [\App\Http\Controllers\Site\NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/autor/{slug}', [AuthorController::class, 'show'])->name('autor');

Route::view('/sobre', 'site.sobre')->name('sobre');
Route::view('/contactos', 'site.contactos')->name('contactos');

// Páginas institucionais (política editorial, privacidade, termos)
Route::get('/{page}', function (string $page) {
    $pages = ['politica-editorial', 'privacidade', 'termos'];
    abort_unless(in_array($page, $pages, true), 404);

    return view('site.institucional', ['page' => $page]);
})->whereIn('page', ['politica-editorial', 'privacidade', 'termos'])->name('pagina');

// Backoffice editorial (acesso restrito por perfil)
Route::middleware(['auth', 'verified', 'can:access-backoffice'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
