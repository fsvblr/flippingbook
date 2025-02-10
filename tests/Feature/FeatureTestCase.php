<?php

namespace Flippingbook\Tests\Feature;

use Orchestra\Testbench\TestCase;

class FeatureTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->withoutVite();
    }

    protected function getPackageProviders($app)
    {
        return [
            'Flippingbook\FlippingbookServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('flippingbook.image_thumb_small_side', 100);
        $app['config']->set('flippingbook.image_thumb_big_side', 150);
        $app['config']->set('flippingbook.image_full_small_side', 400);
        $app['config']->set('flippingbook.image_full_big_side', 600);

        $app['config']->set('filesystems.default', 'public');
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations/0000_00_00_000000_create_flippingbook_table.php');
    }
}
