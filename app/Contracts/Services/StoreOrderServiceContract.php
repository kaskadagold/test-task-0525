<?php

namespace App\Contracts\Services;

use App\Models\Order;

interface StoreOrderServiceContract
{
    /**
     * Create the order
     *
     * @param array $fields
     * @throws \App\Exceptions\WarehouseNotFoundException
     * @return \App\Models\Order|null
     */
    public function create(array $fields): ?Order;
}
