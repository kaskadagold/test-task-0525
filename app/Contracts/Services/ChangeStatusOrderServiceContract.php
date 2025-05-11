<?php

namespace App\Contracts\Services;

use App\Models\Order;

interface ChangeStatusOrderServiceContract
{
    /**
     * Cancel the order.
     *
     * @param \App\Models\Order $order
     * @return Order
     */
    public function cancelOrder(Order $order): Order;

    /**
     * Renew the order.
     *
     * @param \App\Models\Order $order
     * @return Order
     */
    public function renewOrder(Order $order): Order;
}
