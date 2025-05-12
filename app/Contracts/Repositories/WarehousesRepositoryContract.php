<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface WarehousesRepositoryContract
{
    /**
     * Find the list of warehouse entities
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems(): Collection;

    /**
     * Check if the required warehouse exists
     *
     * @param int $id
     * @return bool
     */
    public function checkIfExist(int $id): bool;
}
