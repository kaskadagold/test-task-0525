<?php

namespace App\Contracts\Services;

use App\Models\Order;

interface UpdateOrderServiceContract
{
    /**
     * Update the order.
     *
     * @param \App\Models\Order $order
     * @param array $fields
     * @return Order
     */
    public function update(Order $order, array $fields): Order;
}
