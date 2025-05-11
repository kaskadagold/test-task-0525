<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrdersRepositoryContract;
use App\DTO\FilterOrdersDTO;
use App\Entities\FilterEntity;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class OrdersRepository implements OrdersRepositoryContract
{
    public function __construct(private readonly Order $model)
    {
    }

    /**
     * Get the repository model
     *
     * @return \App\Models\Order
     */
    public function getModel(): Order
    {
        return $this->model;
    }

    /**
     * Find order entity by id
     *
     * @param int $id
     * @param array $relations
     * @throws \App\Exceptions\OrderNotFoundException
     * @return \App\Models\Order
     */
    #[Override]
    public function getById(int $id, array $relations = []): Order
    {
        $order = $this->getModel()
            ->with($relations)
            ->find($id);

        if ($order instanceof Order) {
            return $order;
        }

        throw new OrderNotFoundException($id);
    }

    /**
     * Update order entity in the repository
     *
     * @param \App\Models\Order $order
     * @param array $fields
     * @return \App\Models\Order
     */
    #[Override]
    public function update(Order $order, array $fields): Order
    {
        $order->update($fields);

        return $order;
    }

    /**
     * Add new order entity to the repository
     *
     * @param array $fields
     * @return \App\Models\Order
     */
    #[Override]
    public function create(array $fields): Order
    {
        return $this->getModel()->create($fields);
    }

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
    #[Override]
    public function paginate(
        FilterOrdersDTO $filters,
        array $fields = ['*'],
        int $perPage = 5,
        int $page = 1,
        string $pageName = 'page',
        array $relations = [],
    ): LengthAwarePaginator {
        $query = $this->getModel();
        if ($filters->getWarehouse() !== null) {
            $query = $query->leftJoin('warehouses', 'warehouses.id', '=', 'orders.warehouse_id');
        }

        $this->getFilterQuery($query, 'customer', $filters->getCustomer())
            ->getFilterQuery($query, 'warehouses.name', $filters->getWarehouse())
            ->getFilterQuery($query, 'created_at', $filters->getCreatedAt())
            ->getFilterQuery($query, 'completed_at', $filters->getCompletedAt())
            ->getFilterQuery($query, 'status', $filters->getStatus());

        return $query->with($relations)->paginate($perPage, $fields, $pageName, $page);
    }

    /**
     * Build the query applying the specified filter
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Models\Order $query
     * @param string $field
     * @param mixed $filter
     * @return OrdersRepository
     */
    private function getFilterQuery(Builder | BelongsTo | Order &$query, string $field, ?FilterEntity $filter): static
    {
        if ($filter !== null) {
            $operator = $filter->getOperator();
            $value = $filter->getValue();
            $order = $filter->getOrder();

            if ($operator !== null) {
                if ($operator === 'is_null') {
                    $query = $query->whereNull($field);
                } elseif ($operator === 'is_not_null') {
                    $query = $query->whereNotNull($field);
                } elseif ($value !== null) {
                    $query = $query->where($field, $operator, $value);
                }
            }

            if ($order !== null) {
                ($order === 'order_asc') ?
                    $query = $query->orderBy($field) :
                    $query = $query->orderByDesc($field);
            }
        }

        return $this;
    }
}
