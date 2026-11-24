<?php

use App\Enums\Locale;
use App\Enums\Offering;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\SetLocale;
use App\Support\SiteMarkdown;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/'.Locale::default()->value, 301);

Route::prefix('{locale}')->whereIn('locale', Locale::cases())->middleware(SetLocale::class)->group(function (): void {
    Route::get('/', Site\HomeController::class)->name('home');
    Route::get('/produk', [Site\ProductController::class, 'index'])->name('products.index');
    Route::get('/produk/{product}', [Site\ProductController::class, 'show'])->name('products.show');
    Route::get('/bibit', [Site\ProductController::class, 'seedlings'])->name('seedlings.index');
    Route::get('/buah/{category}', [Site\ProductController::class, 'category'])->name('products.category');
    Route::get('/artikel', [Site\ArticleController::class, 'index'])->name('articles.index');
    Route::get('/artikel/{article}', [Site\ArticleController::class, 'show'])->name('articles.show');
    Route::get('/tentang-kami', [Site\PageController::class, 'about'])->name('about');
    Route::get('/{offering}', [Site\PageController::class, 'offering'])
        ->whereIn('offering', Offering::cases())
        ->name('offerings.show');

    Route::get('/pesan/{product?}', Site\WhatsappController::class)
        ->middleware('throttle:whatsapp')
        ->name('whatsapp.order');
});

// Links from before the website had language prefixes.
Route::get('/{path}', Site\LegacyRedirectController::class)
    ->where('path', '('.implode('|', SiteMarkdown::PAGE_SEGMENTS).')(/.*)?');

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
        Route::get('sampah-produk', [Admin\TrashedProductController::class, 'index'])->name('trashed-products.index');
        Route::patch('sampah-produk/{product}', [Admin\TrashedProductController::class, 'update'])->withTrashed()->name('trashed-products.update');
        Route::resource('articles', Admin\ArticleController::class)->except('show');
        Route::patch('testimonials/{testimonial}/publikasi', [Admin\TestimonialController::class, 'togglePublication'])->name('testimonials.toggle-publication');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::patch('sampah-layanan/{service}', [Admin\TrashedServiceController::class, 'update'])->withTrashed()->name('trashed-services.update');
        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');

        Route::middleware('can:administer')->group(function (): void {
            Route::delete('sampah-produk/{product}', [Admin\TrashedProductController::class, 'destroy'])->withTrashed()->name('trashed-products.destroy');
            Route::resource('users', Admin\UserController::class)->except('show');
            Route::get('pengaturan', [Admin\SettingController::class, 'edit'])->name('settings.edit');
            Route::put('pengaturan', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::get('custom-page/home', [Admin\HomePageController::class, 'edit'])->name('home-page.edit');
            Route::put('custom-page/home', [Admin\HomePageController::class, 'update'])->name('home-page.update');
            Route::get('log-aktivitas', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::get('klik-whatsapp', [Admin\ActivityLogController::class, 'whatsappClicks'])->name('whatsapp-clicks.index');
        });
    });
});
