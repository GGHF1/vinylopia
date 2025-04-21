<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SpotifyService;
use App\Models\Vinyl;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SpotifyService::class, function ($app) {
            return new SpotifyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('vinylsresult', Vinyl::select('vinyl_id', 'title', 'artist', 'cover')->get());
        View::share('vinylRelease', Vinyl::select('vinyl_id', 'barcode')->get());
    }
}
