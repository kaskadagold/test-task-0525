<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrdersRepositoryContract
{
    /**
     * Find the list of order entities
     *
     * @param array $relations
     * @return \Illuminate\Support\Collection
     */
    public function getItems(array $relations = []): Collection;

    /**
     * Find order entity by id
     *
     * @param int $id
     * @param array $relations
     * @return \App\Models\Order
     */
    public function getById(int $id, array $relations = []): Order;

    /**
     * Update order entity in the repository
     *
     * @param \App\Models\Order $order
     * @param array $fields
     * @return \App\Models\Order
     */
    public function update(Order $order, array $fields): Order;

    /**
     * Add new order entity to the repository
     *
     * @param array $fields
     * @return \App\Models\Order
     */
    public function create(array $fields): Order;
}
