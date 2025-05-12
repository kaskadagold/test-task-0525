<?php

namespace App\Providers;

use App\Contracts\Services\ChangeStatusOrderServiceContract;
use App\Contracts\Services\StoreOrderServiceContract;
use App\Contracts\Services\UpdateOrderServiceContract;
use App\Services\OrdersService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StoreOrderServiceContract::class, OrdersService::class);
        $this->app->singleton(UpdateOrderServiceContract::class, OrdersService::class);
        $this->app->singleton(ChangeStatusOrderServiceContract::class, OrdersService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
