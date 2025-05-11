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
}
