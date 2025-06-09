<?php

namespace Arky\Sales\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Arky\Sales\Models\Order;
use Arky\Sales\Observers\OrderObserver;

class SalesProvider extends ServiceProvider
{
/**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the Order observer
        Order::observe(OrderObserver::class);

        // self::registerRoutes();

        include __DIR__ . '/../Http/helpers.php';
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sales');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerRoutes()
    {

        // Route::group($this->routeConfiguration(), function () {
        //     $this->loadRoutesFrom(__DIR__ . '/../routes/web-product.php');
        // });
    }

    private function routeConfiguration()
    {
        return [
            'middleware' => 'web',
        ];
    }
}
