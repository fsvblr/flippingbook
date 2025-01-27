<?php

namespace Flippingbook;

use Illuminate\Support\ServiceProvider;

class FlippingbookServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/flippingbook.php' => config_path('flippingbook.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes/flippingbook.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/flippingbookadmin.php');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'flippingbook');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'flippingbook');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/flippingbook'),
        ], 'public');
    }
}
