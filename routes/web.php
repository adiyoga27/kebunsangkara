<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/

Route::get('/', Site\HomeController::class)->name('home');
Route::get('/durian', [Site\ProductController::class, 'index'])->name('products.index');
Route::get('/durian/{product}', [Site\ProductController::class, 'show'])->name('products.show');
Route::get('/artikel', [Site\ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article}', [Site\ArticleController::class, 'show'])->name('articles.show');
Route::get('/tentang-kami', [Site\PageController::class, 'about'])->name('about');
Route::get('/kontak', [Site\PageController::class, 'contact'])->name('contact');

Route::get('/pesan/{product?}', Site\WhatsappController::class)
    ->middleware('throttle:whatsapp')
    ->name('whatsapp.order');

Route::get('/sitemap.xml', [Site\SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [Site\SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:login')->name('login.store');
    });

    Route::middleware(['auth', EnsureUserIsActive::class])->group(function (): void {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('profil', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profil', [Admin\ProfileController::class, 'update'])->name('profile.update');

        Route::resource('products', Admin\ProductController::class)->except('show');
        Route::resource('articles', Admin\ArticleController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');

        Route::middleware('can:administer')->group(function (): void {
            Route::resource('users', Admin\UserController::class)->except('show');
            Route::get('pengaturan', [Admin\SettingController::class, 'edit'])->name('settings.edit');
            Route::put('pengaturan', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::get('log-aktivitas', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::get('klik-whatsapp', [Admin\ActivityLogController::class, 'whatsappClicks'])->name('whatsapp-clicks.index');
        });
    });
});
