<?php

namespace App\Providers;

use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(SiteSettings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('administer', fn (User $user): bool => $user->isAdmin());

        RateLimiter::for('login', fn (Request $request): Limit => Limit::perMinute(5)->by(
            Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip())
        ));

        RateLimiter::for('whatsapp', fn (Request $request): Limit => Limit::perMinute(30)->by($request->ip()));

        View::composer(['components.layouts.*', 'components.site.*', 'site.*', 'admin.*'], function (ViewInstance $view): void {
            $view->with('site', $this->app->make(SiteSettings::class));
        });

        Paginator::defaultView('pagination::tailwind');
    }
}
