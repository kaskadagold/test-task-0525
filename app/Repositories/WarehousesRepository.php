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

    /**
     * Get the repository model
     *
     * @return \App\Models\Warehouse
     */
    public function getModel(): Warehouse
    {
        return $this->model;
    }

    /**
     * Find the list of warehouse entities
     *
     * @return \Illuminate\Support\Collection
     */
    #[Override]
    public function getItems(): Collection
    {
        return $this->model
            ->orderBy('id')
            ->get();
    }

    /**
     * Check if the required warehouse exists
     *
     * @param int $id
     * @return bool
     */
    #[Override]
    public function checkIfExist(int $id): bool
    {
        return $this->getModel()
            ->where('id', '=', $id)
            ->exists();
    }
}
