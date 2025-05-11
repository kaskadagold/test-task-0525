<?php

namespace App\Contracts\Repositories;

use App\DTO\FilterOrdersDTO;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrdersRepositoryContract
{
    /**
     * Find order entity by id
     *
     * @param int $id
     * @param array $relations
     * @throws \App\Exceptions\OrderNotFoundException
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

    /**
     * Find the list of order entities with the specified filters and pagination
     *
     * @param \App\DTO\FilterOrdersDTO $filters
     * @param array $fields
     * @param int $perPage
     * @param int $page
     * @param string $pageName
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        FilterOrdersDTO $filters,
        array $fields = ['*'],
        int $perPage = 5,
        int $page = 1,
        string $pageName = 'page',
        array $relations = [],
    ): LengthAwarePaginator;
}
