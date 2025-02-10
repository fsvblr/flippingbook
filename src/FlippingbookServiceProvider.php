<?php

namespace Flippingbook;

use Flippingbook\Services\AdminSystemMessageService;
use Flippingbook\Services\PublicationFolderService;
use Illuminate\Support\ServiceProvider;

class FlippingbookServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AdminSystemMessageService::class, function ($app) {
            return new AdminSystemMessageService();
        });

        $this->app->singleton(PublicationFolderService::class, function ($app) {
            return new PublicationFolderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/flippingbook.php' => config_path('flippingbook.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/flippingbook.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/flippingbookadmin.php');

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'flippingbook');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'flippingbook');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/flippingbook'),
        ], 'public');

        $this->app['config']['filesystems.disks.flippingbook'] =
            [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL') . '/storage',
                'visibility' => 'public',
                'throw' => false,
                'report' => false,
            ];
    }
}
