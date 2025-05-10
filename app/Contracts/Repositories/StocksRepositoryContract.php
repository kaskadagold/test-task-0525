<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface StocksRepositoryContract
{
    /**
     * Find the list of stock entities
     *
     * @param int $warehouseId
     * @param mixed $products
     * @return \Illuminate\Support\Collection
     */
    public function getItems(int $warehouseId, ?array $products = null): Collection;
}
