<?php

namespace App\Contracts\Repositories;

use App\Models\OrderItem;
use Illuminate\Support\Collection;

interface OrderItemsRepositoryContract
{
    /**
     * Add new order item entity to the repository
     *
     * @param array $fields
     * @return \App\Models\OrderItem
     */
    public function create(array $fields): OrderItem;

    /**
     * Update the order item entity in the repository
     *
     * @param \App\Models\OrderItem $orderItem
     * @param array $fields
     * @return \App\Models\OrderItem
     */
    public function update(OrderItem $orderItem, array $fields): OrderItem;

    /**
     * Remove the order item entity from the repository
     *
     * @param \App\Models\OrderItem $user
     * @return bool | null
     */
    public function delete(OrderItem $orderItem): bool|null;

    /**
     * Find order items entity by the order id
     *
     * @param int $orderId
     * @return \Illuminate\Support\Collection
     */
    public function getByOrder(int $orderId): Collection;

    /**
     * Find a single order item entity by the order id and the product id
     *
     * @param int $orderId
     * @throws \App\Exceptions\OrderItemNotFoundException
     * @return \App\Models\OrderItem
     */
    public function getByKey(int $orderId, int $productId): OrderItem;
}
