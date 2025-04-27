<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SpotifyService;
use App\Models\Vinyl;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // Logic added to avoid error "Base table or view not found" during migrations
        if (!$this->app->runningInConsole() || 
            !in_array($this->app['request']->server->get('argv')[1] ?? '', 
            ['migrate', 'migrate:fresh', 'migrate:reset', 'migrate:refresh', 'db:wipe'])) {
            
            try {
                View::share('vinylsresult', Vinyl::select('vinyl_id', 'title', 'artist', 'cover')->get());
                View::share('vinylRelease', Vinyl::select('vinyl_id', 'barcode')->get());
            } catch (\Exception $e) {}
        }
    }
}