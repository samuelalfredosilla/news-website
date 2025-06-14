<?php

// routes/web.php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Depan Publik
Route::get('/', function () {
    // Akan diisi nanti dengan daftar berita
    return view('welcome');
});

// Grup Rute yang memerlukan otentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Panel Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Middleware Role untuk seluruh grup admin (opsional, bisa lebih spesifik di controller)
        Route::middleware('role:admin|editor|wartawan')->group(function () {
            // Rute untuk Kategori
            Route::resource('categories', CategoryController::class);
            // Batasi siapa yang bisa akses CRUD kategori hanya Admin & Editor
            Route::middleware('role:admin|editor')->group(function () {
                // Semua rute resource categories sudah dilindungi oleh middleware 'manage categories' di controller
            });


            // Rute untuk Berita
            Route::resource('news_articles', NewsArticleController::class);
            // Tambahan rute untuk publish/unpublish
            Route::put('news_articles/{news_article}/publish', [NewsArticleController::class, 'publish'])->name('news_articles.publish')->middleware('permission:publish news');
            Route::put('news_articles/{news_article}/unpublish', [NewsArticleController::class, 'unpublish'])->name('news_articles.unpublish')->middleware('permission:publish news');
        });

        // Rute Manajemen User (hanya untuk Admin)
        Route::middleware('role:admin')->group(function () {
            // Anda perlu membuat UserController untuk ini
            Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});

// ... (existing routes)

Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/news', [App\Http\Controllers\PublicController::class, 'newsList'])->name('news.list');
Route::get('/news/{slug}', [App\Http\Controllers\PublicController::class, 'newsDetail'])->name('news.detail');
Route::get('/category/{slug}', [App\Http\Controllers\PublicController::class, 'newsByCategory'])->name('news.by_category');

require __DIR__.'/auth.php';