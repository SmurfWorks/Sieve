<?php

namespace SmurfWorks\Sieve;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SieveProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('SmurfWorks\ModelFinder\ModelFinderProvider');
        $this->app->alias(Sieve::class, 'sieve');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sieve'];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->mergeConfigFrom(sprintf('%s/../config/sieve.php', __DIR__), 'sieve');
        $this->loadViewsFrom(sprintf('%s/../resources/views', __DIR__), 'sieve');
        $this->registerRoutes();

        $this->publishes([
            sprintf('%s/../public', __DIR__) => public_path('vendor/sieve'),
        ], 'public');
    }

    /**
     * Register routes used by this service.
     *
     * @return void
     */
    protected function registerRoutes() : void
    {
        if (!config('sieve.routes.enabled')) {
            return;
        }

        Route::group(
            [
                'prefix' => config('sieve.routes.prefix', 'sieve'),
                'middleware' => config('sieve.routes.middleware', []),
            ],
            function () {
                $this->loadRoutesFrom(sprintf('%s/../routes/web.php', __DIR__));
            }
        );
    }
}
