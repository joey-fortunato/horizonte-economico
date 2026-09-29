<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\ArticleController;
use App\Http\Controllers\Site\AuthorController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\NewsletterController;
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
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('artigos')->name('admin.articles.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('index');
        Route::get('/novo', [App\Http\Controllers\Admin\ArticleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/editar', [App\Http\Controllers\Admin\ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('categorias')->name('admin.categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
        Route::put('/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('media')->name('admin.media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/list', [MediaController::class, 'list'])->name('list');
        Route::post('/', [MediaController::class, 'store'])->name('store');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    });

    Route::get('estatisticas', [StatsController::class, 'index'])->name('admin.stats');

    Route::prefix('noticias')->name('admin.news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/historico', [NewsController::class, 'runs'])->name('runs');
        Route::post('/recolher', [NewsController::class, 'collect'])->name('collect');
        Route::get('/{news}', [NewsController::class, 'show'])->whereNumber('news')->name('show');
        Route::post('/{news}/decisao', [NewsController::class, 'decide'])->whereNumber('news')->name('decide');
        Route::post('/{news}/nota', [NewsController::class, 'note'])->whereNumber('news')->name('note');
        Route::post('/{news}/converter', [NewsController::class, 'convert'])->whereNumber('news')->name('convert');
    });

    Route::prefix('utilizadores')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/settings.php';
