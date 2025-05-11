<?php

namespace App\Providers;

use App\Contracts\Repositories\OrderItemsRepositoryContract;
use App\Contracts\Repositories\OrdersRepositoryContract;
use App\Contracts\Repositories\ProductsRepositoryContract;
use App\Contracts\Repositories\StocksRepositoryContract;
use App\Contracts\Repositories\WarehousesRepositoryContract;
use App\Repositories\OrderItemsRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\StocksRepository;
use App\Repositories\WarehousesRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WarehousesRepositoryContract::class, WarehousesRepository::class);
        $this->app->singleton(ProductsRepositoryContract::class, ProductsRepository::class);
        $this->app->singleton(OrdersRepositoryContract::class, OrdersRepository::class);
        $this->app->singleton(StocksRepositoryContract::class, StocksRepository::class);
        $this->app->singleton(OrderItemsRepositoryContract::class, OrderItemsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
