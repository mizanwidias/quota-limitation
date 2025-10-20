<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Connection;

class ClickhouseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind('clickhouse', function () {
            return new Connection(config('database.connections.clickhouse'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
