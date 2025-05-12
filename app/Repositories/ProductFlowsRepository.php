<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductFlowsRepositoryContract;
use App\DTO\FilterProductFlowsDTO;
use App\Entities\FilterEntity;
use App\Models\ProductFlow;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class ProductFlowsRepository implements ProductFlowsRepositoryContract
{
    public function __construct(private readonly ProductFlow $model)
    {
    }

    /**
     * Get the repository model
     *
     * @return ProductFlow
     */
    public function getModel(): ProductFlow
    {
        return $this->model;
    }

    /**
     * Add new product flow entity to the repository
     *
     * @param array $fields
     * @return ProductFlow|\Illuminate\Database\Eloquent\Model
     */
    #[Override]
    public function create(array $fields): ProductFlow
    {
        $productFlow = $this->getModel()->create($fields);

        return $productFlow;
    }

    /**
     * Find the list of product flow entities with the specified filters and pagination
     *
     * @param \App\DTO\FilterProductFlowsDTO $filters
     * @param array $fields
     * @param int $perPage
     * @param int $page
     * @param string $pageName
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Override]
    public function paginate(
        FilterProductFlowsDTO $filters,
        array $fields = ['*'],
        int $perPage = 5,
        int $page = 1,
        string $pageName = 'page',
        array $relations = [],
    ): LengthAwarePaginator {
        $query = $this->getModel();
        if ($filters->getWarehouse() !== null) {
            if (is_string($filters->getWarehouse()->getValue())) {
                $query = $query->leftJoin('warehouses', 'warehouses.id', '=', 'product_flows.warehouse_id');
                $this->getFilterQuery($query, 'warehouses.name', $filters->getWarehouse());
            } else {
                $this->getFilterQuery($query, 'warehouse_id', $filters->getWarehouse());
            }
        }

        if ($filters->getProduct() !== null) {
            if (is_string($filters->getProduct()->getValue())) {
                $query = $query->leftJoin('products', 'products.id', '=', 'product_flows.product_id');
                $this->getFilterQuery($query, 'products.name', $filters->getProduct());
            } else {
                $this->getFilterQuery($query, 'product_id', $filters->getProduct());
            }
        }

        $this->getFilterQuery($query, 'created_at', $filters->getCreatedAt());

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
    private function getFilterQuery(Builder | BelongsTo | ProductFlow &$query, string $field, ?FilterEntity $filter): static
    {
        if ($filter !== null) {
            $operator = $filter->getOperator();
            $value = $filter->getValue();
            $order = $filter->getOrder();
            $valueStart = $filter->getValueStart();
            $valueEnd = $filter->getValueEnd();

            if ($operator !== null) {
                if ($operator === 'between' && $valueStart !== null && $valueEnd !== null) {
                    $query = $query->whereBetween($field, [$valueStart, $valueEnd]);
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
