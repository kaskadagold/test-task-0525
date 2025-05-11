<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderItemsRepositoryContract;
use App\Exceptions\OrderNotFoundException;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Override;

class OrderItemsRepository implements OrderItemsRepositoryContract
{
    public function __construct(private readonly OrderItem $model)
    {
    }

    /**
     * Get the repository model
     *
     * @return \App\Models\OrderItem
     */
    public function getModel(): OrderItem
    {
        return $this->model;
    }

    /**
     * Add new order item entity to the repository
     *
     * @param array $fields
     * @return \App\Models\OrderItem
     */
    #[Override]
    public function create(array $fields): OrderItem
    {
        return $this->getModel()->create($fields);
    }

    /**
     * Find order items entity by the order id
     *
     * @param int $orderId
     * @return \Illuminate\Support\Collection
     */
    #[Override]
    public function getByOrder(int $orderId): Collection
    {
        return $this->getModel()
            ->where('order_id', '=', $orderId)
            ->get();
    }

    /**
     * Update the order item entity in the repository
     *
     * @param \App\Models\OrderItem $orderItem
     * @param array $fields
     * @return \App\Models\OrderItem
     */
    #[Override]
    public function update(OrderItem $orderItem, array $fields): OrderItem
    {
        $orderItem->update($fields);

        return $orderItem;
    }

    /**
     * Remove the order item entity from the repository
     *
     * @param \App\Models\OrderItem $user
     * @return bool | null
     */
    #[Override]
    public function delete(OrderItem $orderItem): bool|null
    {
        return $orderItem->delete();
    }

    /**
     * Find a single order item entity by the order id and the product id
     *
     * @param int $orderId
     * @throws \App\Exceptions\OrderItemNotFoundException
     * @return \App\Models\OrderItem
     */
    #[Override]
    public function getByKey(int $orderId, int $productId): OrderItem
    {
        $orderItem = $this->getModel()
            ->where('order_id', '=', $orderId)
            ->where('product_id', '=', $productId)
            ->first();

        if ($orderItem instanceof OrderItem) {
            return $orderItem;
        }

        throw new OrderNotFoundException($orderId, $productId);
    }
}
