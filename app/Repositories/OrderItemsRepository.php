<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderItemsRepositoryContract;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Override;

class OrderItemsRepository implements OrderItemsRepositoryContract
{
    public function __construct(private readonly OrderItem $model)
    {
    }

    public function getModel(): OrderItem
    {
        return $this->model;
    }

    #[Override]
    public function create(array $fields): OrderItem
    {
        return $this->getModel()->create($fields);
    }

    #[Override]
    public function getByOrder(int $orderId): Collection
    {
        return $this->getModel()
            ->where('order_id', '=', $orderId)
            ->get();
    }

    #[Override]
    public function update(OrderItem $orderItem, array $fields): OrderItem
    {
        $orderItem->update($fields);

        return $orderItem;
    }

    #[Override]
    public function delete(OrderItem $orderItem): bool|null
    {
        return $orderItem->delete();
    }

    #[Override]
    public function getByKey(int $orderId, int $productId): OrderItem
    {
        return $this->getModel()
            ->where('order_id', '=', $orderId)
            ->where('product_id', '=', $productId)
            ->first();
    }
}
