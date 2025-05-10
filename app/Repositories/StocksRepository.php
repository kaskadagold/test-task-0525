<?php

namespace App\Repositories;

use App\Contracts\Repositories\StocksRepositoryContract;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Override;

class StocksRepository implements StocksRepositoryContract
{
    public function __construct(private readonly Stock $model)
    {
    }

    public function getModel(): Stock
    {
        return $this->model;
    }

    #[Override]
    public function getItems(int $warehouseId, ?array $products = null): Collection
    {
        return $this->getModel()
            ->where('warehouse_id', '=', $warehouseId)
            ->when($products, fn($query) => $query->whereIn('product_id', $products))
            ->get();
    }
}
