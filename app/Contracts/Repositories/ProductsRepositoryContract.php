<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductsRepositoryContract
{
    /**
     * Find the list of product entities
     *
     * @param array $relations
     * @return \Illuminate\Support\Collection
     */
    public function getItems(array $relations = []): Collection;

    /**
     * Find product entity by id
     *
     * @param int $id
     * @param array $relations
     * @return \App\Models\Product
     */
    public function getById(int $id, array $relations = []): Product;
}
