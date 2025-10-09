<?php

namespace App\Providers;

// use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\ServiceProvider;
// use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind('db.connection.clickhouse', function ($app, $parameters) {
        //     $config = $parameters['config'];

        //     return new Connection($config);
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
