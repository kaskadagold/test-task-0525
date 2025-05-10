<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductsRepositoryContract;
use App\Models\Product;
use Illuminate\Support\Collection;
use Override;

class ProductsRepository implements ProductsRepositoryContract
{
    public function __construct(private readonly Product $model)
    {
    }

    public function getModel(): Product
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
    public function getById(int $id, array $relations = []): Product
    {
        return $this->getModel()
            ->with($relations)
            ->find($id);
    }
}
