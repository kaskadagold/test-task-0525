<?php

namespace App\Repositories;

use App\Contracts\Repositories\WarehousesRepositoryContract;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Override;

class WarehousesRepository implements WarehousesRepositoryContract
{
    public function __construct(private readonly Warehouse $model)
    {
    }

    public function getModel(): Warehouse
    {
        return $this->model;
    }

    #[Override]
    public function getItems(): Collection
    {
        return $this->model
            ->orderBy('id')
            ->get();
    }
}
