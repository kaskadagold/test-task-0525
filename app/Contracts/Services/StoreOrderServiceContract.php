<?php

namespace App\Contracts\Services;

use App\Models\Order;

interface StoreOrderServiceContract
{
    /**
     * Create the order
     *
     * @param array $fields
     * @return \App\Models\Order|null
     */
    public function create(array $fields): ?Order;
}
