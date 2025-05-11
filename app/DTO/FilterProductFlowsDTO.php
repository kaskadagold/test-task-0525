<?php

namespace App\DTO;

use App\Entities\FilterEntity;

class FilterProductFlowsDTO
{
    private ?FilterEntity $warehouse = null;
    private ?FilterEntity $product = null;
    private ?FilterEntity $created_at = null;

    public function __construct(array $filters = [])
    {
        foreach ($filters as $filter => $properties) {
            $this->$filter = new FilterEntity($properties);
        }
    }

    public function getWarehouse(): ?FilterEntity
    {
        return $this->warehouse;
    }

    public function getProduct(): ?FilterEntity
    {
        return $this->product;
    }

    public function getCreatedAt(): ?FilterEntity
    {
        return $this->created_at;
    }
}
