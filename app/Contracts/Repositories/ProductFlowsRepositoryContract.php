<?php

namespace App\Contracts\Repositories;

use App\DTO\FilterProductFlowsDTO;
use App\Models\ProductFlow;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductFlowsRepositoryContract
{
    /**
     * Add new product flow entity to the repository
     *
     * @param array $fields
     * @return ProductFlow|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $fields): ProductFlow;

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
    public function paginate(
        FilterProductFlowsDTO $filters,
        array $fields = ['*'],
        int $perPage = 5,
        int $page = 1,
        string $pageName = 'page',
        array $relations = [],
    ): LengthAwarePaginator;
}
