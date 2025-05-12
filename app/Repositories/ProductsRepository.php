<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductsRepositoryContract;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use Illuminate\Support\Collection;
use Override;

class ProductsRepository implements ProductsRepositoryContract
{
    public function __construct(private readonly Product $model)
    {
    }

    /**
     * Get the repository model
     *
     * @return \App\Models\Product
     */
    public function getModel(): Product
    {
        return $this->model;
    }

    /**
     * Find the list of product entities
     *
     * @param array $relations
     * @return \Illuminate\Support\Collection
     */
    #[Override]
    public function getItems(array $relations = []): Collection
    {
        return $this->getModel()
            ->with($relations)
            ->orderBy('id')
            ->get();
    }

    /**
     * Find product entity by id
     *
     * @param int $id
     * @param array $relations
     * @throws \App\Exceptions\ProductNotFoundException
     * @return \App\Models\Product
     */
    #[Override]
    public function getById(int $id, array $relations = []): Product
    {
        $product = $this->getModel()
            ->with($relations)
            ->find($id);

        if ($product instanceof Product) {
            return $product;
        }

        throw new ProductNotFoundException($id);
    }
}
