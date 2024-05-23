<?php

namespace Pderas\TwoFactor;

use Illuminate\Support\ServiceProvider;

class TwoFactorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/2fa.php', '2fa'
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // These migrations run automatically when `php artisan migrate` is executed
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish the migration files
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'two-factor-migrations');

        // Publish the config file and views
        $this->publishes([
            __DIR__ . '/../config/2fa.php' => config_path('2fa.php'),
            __DIR__.'/../resources/views' => resource_path('js/pages/Auth'),
        ], 'two-factor');
    }
}
