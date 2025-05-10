<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrdersRepositoryContract;
use App\Models\Order;
use Illuminate\Support\Collection;
use Override;

class OrdersRepository implements OrdersRepositoryContract
{
    public function __construct(private readonly Order $model)
    {
    }

    public function getModel(): Order
    {
        return $this->model;
    }

    #[Override]
    public function getItems(array $relations = []): Collection
    {
        return $this->getModel()
            ->with($relations)
            ->orderBy('id')
            ->get();
    }

    #[Override]
    public function getById(int $id, array $relations = []): Order
    {
        return $this->getModel()
            ->with($relations)
            ->find($id);
    }

    #[Override]
    public function update(Order $order, array $fields): Order
    {
        $order->update($fields);

        return $order;
    }

    #[Override]
    public function create(array $fields): Order
    {
        return $this->getModel()->create($fields);
    }
}
